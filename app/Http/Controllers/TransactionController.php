<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Transaction;
use App\Models\Vehicle;
use App\Models\STNKRecord;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;


class TransactionController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->search;

        // Cari berdasarkan No Invoice, nopol, atau nama klien
        $transactions = Transaction::with('vehicle.client')
            ->when($search, function ($query, $search) {
                return $query->where('invoice_no', 'like', "%{$search}%")
                             ->orWhere('status_proses', 'like', "%{$search}%")
                             ->orWhereHas('vehicle', function($q) use ($search) {
                                 $q->where('nopol', 'like', "%{$search}%")
                                   ->orWhereHas('client', function($q2) use ($search) {
                                       $q2->where('nama_lengkap', 'like', "%{$search}%");
                                   });
                             });
            })->latest()->paginate(10)->withQueryString();
        return view('transactions.index', compact('transactions'));
    }

    public function create(Request $request)
    {
        // Ambil semua klien untuk dropdown
        $clients = Client::orderBy('nama_lengkap', 'asc')->get();

        $selectedClient = null;
        $vehicles = [];

        // Jika admin sudah memilih klien (via dropdown), ambil daftar kendaraannya
        if ($request->filled('client_id')) {
            $selectedClient = Client::find($request->client_id);
            // Ambil kendaraan yang dimiliki klien ini beserta dokumennya
            $vehicles = Vehicle::with('documents')
                        ->where('client_id', $request->client_id)
                        ->get();
        }
        return view('transactions.create', compact('clients', 'selectedClient', 'vehicles'));
    }

    // simpan transaksi baru dan buat invoice otomatis
    public function store(Request $request)
    {
        $dataTransaksi = $request->input('transaksi');
        
        // 1. Validasi Awal
        if (!$dataTransaksi || !collect($dataTransaksi)->pluck('vehicle_id')->filter()->isNotEmpty()) {
            return redirect()->back()->with('error', 'Silakan tambahkan baris dan pilih minimal 1 kendaraan untuk diproses!');
        }

        $request->validate([
            'tgl_masuk'        => 'required|date',
            'tgl_selesai'      => 'nullable|date|after_or_equal:tgl_masuk',
            'tgl_mulai_termin' => 'required|date',
            'tgl_jatuh_tempo'  => 'required|date|after_or_equal:tgl_mulai_termin', // Cegah tanggal mundur
        ]);

        // 2. VALIDASI DOKUMEN DINAMIS (Kolektif)
        $syaratDokumen = [
            'Pajak Tahunan'    => ['STNK', 'KTP'],
            'Pajak 5 Tahunan'  => ['STNK', 'KTP', 'BPKB', 'Hasil Cek Fisik Kendaraan'],
            'Balik Nama'       => ['STNK', 'KTP Pemilik Baru', 'BPKB', 'Kwitansi Jual Beli'],
            'Mutasi Keluar'    => ['STNK', 'KTP', 'BPKB Asli', 'Hasil Cek Fisik Kendaraan'],
        ];

        foreach ($dataTransaksi as $index => $trx) {
            // Ambil ID Kendaraan dari deopdown form
            $vehicleId = $trx['vehicle_id'] ?? null;
            if ($vehicleId) {
                $vehicle = Vehicle::with('documents')->find($vehicleId);
                $dokumenTersedia = $vehicle->documents->pluck('jenis_dokumen')->toArray();
                
                $layanan = $trx['jenis_layanan'];
                $dokumenWajib = $syaratDokumen[$layanan] ?? ['KTP', 'STNK'];
                
                $dokumenKurang = array_diff($dokumenWajib, $dokumenTersedia);

                // Jika ada 1 saja kendaraan yang kurang dokumen, batalkan semua transaksi
                if (!empty($dokumenKurang)) {
                    $pesan = 'Transaksi ditolak! Dokumen kendaraan ' . $vehicle->nopol . ' belum lengkap. Wajib melampirkan: ' . implode(', ', $dokumenKurang);
                    return redirect()->back()->withInput()->with('error', $pesan);
                }
            }
        }

        // 3. SIMPAN TRANSAKSI KE DATABASE
        // Buat 1 Nomor Invoice untuk semua kendaraan di form ini
        $baseInvoiceNo = 'INV-' . date('Ymd') . '-' . rand(1000, 9999);
        $tglMasuk = $request->input('tgl_masuk');
        $tglSelesai = $request->input('tgl_selesai');
        $urutan = 1; // Variabel penghitung urutan kendaraan

        $tglMulai = $request->input('tgl_mulai_termin');
        $tglJatuh = $request->input('tgl_jatuh_tempo');
        $catatanTermin = null;
        
        if ($tglMulai && $tglJatuh) {
            // Hitung selisih hari menggunakan Carbon
            $selisihHari = \Carbon\Carbon::parse($tglMulai)->diffInDays(\Carbon\Carbon::parse($tglJatuh));
            
            // Rangkai kalimat ciamiknya!
            $catatanTermin = 'Pembayaran tagihan ini memiliki termin <strong>' . $selisihHari . ' Hari</strong> terhitung sejak tanggal berkas diterima (' . \Carbon\Carbon::parse($tglMulai)->format('d/m/Y') . '). Jatuh tempo pembayaran pada tanggal <strong class="text-red-600">' . \Carbon\Carbon::parse($tglJatuh)->format('d/m/Y') . '</strong>.';
        }

        // --- TAMBAHAN BARU: AMBIL INPUT BIAYA GLOBAL DARI FORM ---
        $globalBiaya = $request->input('global_biaya', []);
        
        $lp   = (int) ($globalBiaya['loket_pendaftaran'] ?? 0);
        $lcf  = (int) ($globalBiaya['loket_cek_fisik'] ?? 0);
        $acc  = (int) ($globalBiaya['acc_tidak_hadir'] ?? 0);
        $dom  = (int) ($globalBiaya['acc_domisili'] ?? 0);
        $pntp = (int) ($globalBiaya['loket_penetapan'] ?? 0);
        $psh1 = (int) ($globalBiaya['loket_pengesahan_1'] ?? 0);
        $psh2 = (int) ($globalBiaya['loket_pengesahan_2'] ?? 0);
        $mtr  = (int) ($globalBiaya['bea_materai'] ?? 0);

        // 4. SIMPAN TRANSAKSI KE DATABASE (LOOPING)
        $urutan = 1; 

        foreach ($dataTransaksi as $index => $trx) {
            $vehicleId = $trx['vehicle_id'] ?? null;

            // Eksekusi HANYA jika baris tersebut kendaraannya sudah dipilih
            if ($vehicleId) {
                // Modifikasi invoice agar berakhiran -1, -2, dst supaya lolos dari aturan UNIQUE database
                $invoiceNo = $baseInvoiceNo . '-' . $urutan;
                $urutan++;
                
                // Konversi semua input harga menjadi integer (default 0 jika kosong)
                $pajak = (int) ($trx['biaya_pajak'] ?? 0);
                $jasa  = (int) ($trx['biaya_jasa'] ?? 0);
                
                $grandTotalBiayaLain = $lp + $lcf + $acc + $dom +
                                       $pntp + $psh1 + $psh2 + $mtr;

                $grandTotal = $pajak + $jasa + $grandTotalBiayaLain;

                Transaction::create([
                    'invoice_no'         => $invoiceNo,
                    'vehicle_id'         => $vehicleId,
                    'jenis_layanan'      => $trx['jenis_layanan'],
                    'status_proses'      => 'Pending', // Force status default untuk form Create
                    'biaya_pajak'        => $pajak,
                    'biaya_jasa'         => $jasa,
                    // Variable biaya yg sudah di filter
                    'loket_pendaftaran'  => $lp,
                    'loket_cek_fisik'    => $lcf,
                    'acc_tidak_hadir'    => $acc,
                    'acc_domisili'       => $dom,
                    'loket_penetapan'    => $pntp,
                    'loket_pengesahan_1' => $psh1,
                    'loket_pengesahan_2' => $psh2,
                    'bea_materai'        => $mtr,
                    'biaya_lain'         => $grandTotalBiayaLain,
                    'total_biaya'        => $grandTotal,
                    'tgl_masuk'          => $tglMasuk,
                    'tgl_selesai'        => $tglSelesai,
                    'tgl_mulai_termin'   => $tglMulai,
                    'tgl_jatuh_tempo'    => $tglJatuh,
                    'catatan'            => $catatanTermin,
                ]);
            }
        }

        return redirect()->route('transactions.index')->with('success', 'Transaksi baru berhasil ditambahkan dengan Nomor: ' . $baseInvoiceNo);

    }

    public function show(string $id)
    {
        //
    }

    public function edit(string $id)
    {
        $trx = Transaction::findOrFail($id);
        // 2. Hapus angka urutan di belakang (-1, -2) untuk mendapatkan Base Invoice
        $baseInvoice = preg_replace('/-\d+$/', '', $trx->invoice_no);

        // 3. Tarik SEMUA transaksi yang satu rombongan (memiliki Base Invoice yang sama)
        $transactions = \App\Models\Transaction::with('vehicle.client')
            ->where('invoice_no', 'like', $baseInvoice . '-%')
            ->get();

        // 4. Ambil data klien dari transaksi pertama
        $client = $transactions->first()->vehicle->client ?? null;
        
        // 5. [BARU] Ambil data seluruh kendaraan milik klien ini untuk pilihan Dropdown
        $vehicles = [];
        if ($client) {
            $vehicles = \App\Models\Vehicle::where('client_id', $client->id)->get();
        }

        // daftar status proses yang bisa dipilih
        $statuses = [
            'Pending', 'Done', 'Cancel'
        ];
        
        return view('transactions.edit', compact('transactions', 'baseInvoice', 'client', 'statuses', 'vehicles'));
    }

    // perbarui status pengurusan biro jasa
    public function update(Request $request, string $id)
    {
        $dataTransaksi = $request->input('transaksi');

        // 1. Validasi pastikan minimal ada 1 kendaraan yang dipilih dari dropdown
        if (!$dataTransaksi || !collect($dataTransaksi)->pluck('vehicle_id')->filter()->isNotEmpty()) {
            return redirect()->back()->with('error', 'Minimal 1 kendaraan harus dipilih!');
        }

        $request->validate([
            'tgl_masuk'        => 'required|date',
            'tgl_selesai'      => 'nullable|date|after_or_equal:tgl_masuk',
            'tgl_mulai_termin' => 'required|date',
            'tgl_jatuh_tempo'  => 'required|date|after_or_equal:tgl_mulai_termin', // Cegah tanggal mundur
        ]);
        
        $tglMulai = $request->input('tgl_mulai_termin');
        $tglJatuh = $request->input('tgl_jatuh_tempo');
        $catatanTermin = null;
        
        if ($tglMulai && $tglJatuh) {
            // Hitung selisih hari menggunakan Carbon
            $selisihHari = \Carbon\Carbon::parse($tglMulai)->diffInDays(\Carbon\Carbon::parse($tglJatuh));
            // Rangkai kalimat ciamiknya!
            $catatanTermin = 'Pembayaran tagihan ini memiliki termin <strong>' . $selisihHari . ' Hari</strong> terhitung sejak tanggal berkas diterima (' . \Carbon\Carbon::parse($tglMulai)->format('d/m/Y') . '). Jatuh tempo pembayaran pada tanggal <strong class="text-red-600">' . \Carbon\Carbon::parse($tglJatuh)->format('d/m/Y') . '</strong>.';
        }
        
        $tglMasukGlobal = $request->input('tgl_masuk');
        $tglSelesaiGlobal = $request->input('tgl_selesai');

        // [BARU] Ambil data biaya Global (Sistem Bulk Apply)
        $globalBiaya = $request->input('global_biaya', []);
        $lp   = (int) ($globalBiaya['loket_pendaftaran'] ?? 0);
        $lcf  = (int) ($globalBiaya['loket_cek_fisik'] ?? 0);
        $acc  = (int) ($globalBiaya['acc_tidak_hadir'] ?? 0);
        $dom  = (int) ($globalBiaya['acc_domisili'] ?? 0);
        $pntp = (int) ($globalBiaya['loket_penetapan'] ?? 0);
        $psh1 = (int) ($globalBiaya['loket_pengesahan_1'] ?? 0);
        $psh2 = (int) ($globalBiaya['loket_pengesahan_2'] ?? 0);
        $mtr  = (int) ($globalBiaya['bea_materai'] ?? 0);

        $grandTotalBiayaLain = $lp + $lcf + $acc + $dom + $pntp + $psh1 + $psh2 + $mtr;

        // Persiapan Base Invoice No
        $baseTrx = Transaction::findOrFail($id);
        $baseInvoiceNo = preg_replace('/-\d+$/', '', $baseTrx->invoice_no);

        // [BARU] Hapus baris transaksi lama jika Admin menekan tombol X di form Edit
        $submittedIds = collect($dataTransaksi)->pluck('id')->filter()->toArray();
        Transaction::where('invoice_no', 'LIKE', $baseInvoiceNo . '-%')
            ->whereNotIn('id', $submittedIds)
            ->delete();

        // Lakukan perulangan untuk mengupdate SETIAP baris kendaraan
        $urutan = 1;
        foreach ($dataTransaksi as $index => $data) {
            $vehicleId = $data['vehicle_id'] ?? null;
            
            // Eksekusi HANYA jika dropdown kendaraan di baris ini tidak kosong
            if ($vehicleId) {
                // Tata ulang nomor invoice (agar selalu urut -1, -2, -3 meski ada baris yg dihapus)
                $invoiceNo = $baseInvoiceNo . '-' . $urutan;
                $urutan++;

                $statusBaru = $data['status_proses'] ?? 'Pending';
                
                // Atur tanggal selesai secara cerdas
                $tglSelesai = $tglSelesaiGlobal;
                if ($statusBaru == 'Done' && empty($tglSelesai)) {
                    $tglSelesai = date('Y-m-d'); // Auto-fill hari ini jika Done
                } elseif ($statusBaru == 'Cancel') {
                    $tglSelesai = null; // Kosongkan jika dibatalkan
                }

                $pajak = (int) ($data['biaya_pajak'] ?? 0);
                $jasa  = (int) ($data['biaya_jasa'] ?? 0);
                $grandTotal = $pajak + $jasa + $grandTotalBiayaLain;

                $dataToSave = [
                    'invoice_no'         => $invoiceNo,
                    'vehicle_id'         => $vehicleId,
                    'jenis_layanan'      => $data['jenis_layanan'],
                    'status_proses'      => $statusBaru,
                    'biaya_pajak'        => $pajak,
                    'biaya_jasa'         => $jasa,
                    'loket_pendaftaran'  => $lp,
                    'loket_cek_fisik'    => $lcf,
                    'acc_tidak_hadir'    => $acc,
                    'acc_domisili'       => $dom,
                    'loket_penetapan'    => $pntp,
                    'loket_pengesahan_1' => $psh1,
                    'loket_pengesahan_2' => $psh2,
                    'bea_materai'        => $mtr,
                    'biaya_lain'         => $grandTotalBiayaLain,
                    'total_biaya'        => $grandTotal,
                    'tgl_masuk'          => $tglMasukGlobal,
                    'tgl_selesai'        => $tglSelesai,
                    'tgl_mulai_termin'   => $tglMulai,
                    'tgl_jatuh_tempo'    => $tglJatuh,
                    'catatan'            => $catatanTermin,
                ];

                // CEK APAKAH INI DATA LAMA (UPDATE) ATAU BARIS BARU (CREATE)
                if (!empty($data['id'])) {
                    $transaction = Transaction::find($data['id']);
                    
                    if ($transaction) {
                        $statusLama = $transaction->status_proses;
                        
                        // Eksekusi Update
                        $transaction->update($dataToSave);

                        // FITUR OTOMATISASI PERPANJANGAN PAJAK (AUTO-RENEW)
                        if ($statusLama != 'Done' && $statusBaru == 'Done') {
                            $stnk = \App\Models\STNKRecord::where('vehicle_id', $transaction->vehicle_id)->first();
                            if ($stnk) {
                                if ($transaction->jenis_layanan == 'Pajak Tahunan' && $stnk->tgl_jatuh_tempo_pajak) {
                                    $stnk->tgl_jatuh_tempo_pajak = \Carbon\Carbon::parse($stnk->tgl_jatuh_tempo_pajak)->addYear();
                                } 
                                elseif ($transaction->jenis_layanan == 'Pajak 5 Tahunan') { 
                                    if ($stnk->tgl_jatuh_tempo_pajak) {
                                        $stnk->tgl_jatuh_tempo_pajak = \Carbon\Carbon::parse($stnk->tgl_jatuh_tempo_pajak)->addYear();
                                    }
                                    if ($stnk->tgl_habis_stnk) {
                                        $stnk->tgl_habis_stnk = \Carbon\Carbon::parse($stnk->tgl_habis_stnk)->addYear(5);
                                    }
                                }
                                $stnk->save();
                            }
                        }

                        // FITUR PENGURANGAN PAJAK KETIKA STATUS MENJADI CANCEL
                        if ($statusLama == 'Done' && $statusBaru == 'Cancel') {
                            $stnk = \App\Models\STNKRecord::where('vehicle_id', $transaction->vehicle_id)->first();
                            if ($stnk) {
                                if ($transaction->jenis_layanan == 'Pajak Tahunan' && $stnk->tgl_jatuh_tempo_pajak) {
                                    $stnk->tgl_jatuh_tempo_pajak = \Carbon\Carbon::parse($stnk->tgl_jatuh_tempo_pajak)->subYear();
                                } 
                                elseif ($transaction->jenis_layanan == 'Pajak 5 Tahunan') { 
                                    if ($stnk->tgl_jatuh_tempo_pajak) {
                                        $stnk->tgl_jatuh_tempo_pajak = \Carbon\Carbon::parse($stnk->tgl_jatuh_tempo_pajak)->subYear();
                                    }
                                    if ($stnk->tgl_habis_stnk) {
                                        $stnk->tgl_habis_stnk = \Carbon\Carbon::parse($stnk->tgl_habis_stnk)->subYear(5);
                                    }
                                }
                                $stnk->save();
                            }
                            $transaction->update(['tgl_selesai' => null]);
                        }
                    }
                } else {
                    // JIKA BARIS BARU DITAMBAHKAN SAAT EDIT (CREATE)
                    Transaction::create($dataToSave);
                }
            }
        }

        return redirect()->route('transactions.index')->with('success', 'Transaksi berhasil diperbarui! Jika status Selesai (Done), masa aktif STNK telah diperpanjang otomatis.');
    }

    public function destroy(string $id)
    {
        $transaction = Transaction::findOrFail($id);
        $transaction->delete();
        return redirect()->route('transactions.index')->with('success', 'Transaksi berhasil dihapus.');
    }

    public function print($id) 
    {
        // 1. Cari transaksi yang tombol print-nya diklik
        $trx = Transaction::findOrFail($id);
        // 2. Dapatkan Base Invoice (Hapus akhiran -1, -2, dst jika ada)
        $baseInvoice = preg_replace('/-\d+$/', '', $trx->invoice_no);
        // 3. Ambil SEMUA transaksi yang berawalan Base Invoice tersebut
        $transactions = Transaction::with('vehicle.client')
            ->where('invoice_no', 'like', $baseInvoice . '%')
            ->get();
        return view('transactions.print', compact('transactions', 'baseInvoice'));
    }

    public function sendReminder($vehicle_id)
    {
        $vehicle = Vehicle::with('client', 'stnk')->findOrFail($vehicle_id);

        if (!$vehicle->client->email) {
            return redirect()->back()->with('error', 'Gagal! Klien ini belum memiliki alamat email di database.');
        }

        // Kirim email
        Mail::to($vehicle->client->email)->send(new \App\Mail\TaxReminderMail($vehicle));

        return redirect()->back()->with('success', 'Pengingat berhasil dikirim ke ' . $vehicle->client->email);
    }


}
