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
            $selectedClient = \App\Models\Client::find($request->client_id);
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
        if (!$dataTransaksi || !collect($dataTransaksi)->contains('is_selected', '1')) {
            return redirect()->back()->with('error', 'Pilih dan centang minimal 1 kendaraan untuk diproses!');
        }

        $request->validate([
            'tgl_masuk' => 'required|date',
            'tgl_selesai' => 'nullable|date|after_or_equal:tgl_masuk',
        ]);

        // 2. VALIDASI DOKUMEN DINAMIS (Kolektif)
        $syaratDokumen = [
            'Pajak Tahunan'    => ['STNK', 'KTP'],
            'Pajak 5 Tahunan'  => ['STNK', 'KTP', 'BPKB', 'Hasil Cek Fisik Kendaraan'],
            'Balik Nama'       => ['STNK', 'KTP Pemilik Baru', 'BPKB', 'Kwitansi Jual Beli'],
            'Mutasi Keluar'    => ['STNK', 'KTP', 'BPKB Asli', 'Hasil Cek Fisik Kendaraan'],
        ];

        foreach ($dataTransaksi as $vehicleId => $trx) {
            // Hanya periksa kendaraan yang dicentang
            if (isset($trx['is_selected']) && $trx['is_selected'] == '1') {
                $vehicle = \App\Models\Vehicle::with('documents')->find($vehicleId);
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

        foreach ($dataTransaksi as $vehicleId => $trx) {
            if (isset($trx['is_selected']) && $trx['is_selected'] == '1') {
                // Modifikasi invoice agar berakhiran -1, -2, dst supaya lolos dari aturan UNIQUE database
                $invoiceNo = $baseInvoiceNo . '-' . $urutan;
                $urutan++;
                
                // Konversi semua input harga menjadi integer (default 0 jika kosong)
                $pajak = (int) ($trx['biaya_pajak'] ?? 0);
                $jasa  = (int) ($trx['biaya_jasa'] ?? 0);
                
                $loketPendaftaran = (int) ($trx['loket_pendaftaran'] ?? 0);
                $loketCekFisik    = (int) ($trx['loket_cek_fisik'] ?? 0);
                $accTidakHadir    = (int) ($trx['acc_tidak_hadir'] ?? 0);
                $accDomisili      = (int) ($trx['acc_domisili'] ?? 0);
                $loketPenetapan   = (int) ($trx['loket_penetapan'] ?? 0);
                $loketPengesahan1 = (int) ($trx['loket_pengesahan_1'] ?? 0);
                $loketPengesahan2 = (int) ($trx['loket_pengesahan_2'] ?? 0);
                $beaMaterai       = (int) ($trx['bea_materai'] ?? 0);

                $grandTotalBiayaLain = $loketPendaftaran + $loketCekFisik + $accTidakHadir + $accDomisili +
                                       $loketPenetapan + $loketPengesahan1 + $loketPengesahan2 + $beaMaterai;

                $grandTotal = $pajak + $jasa + $grandTotalBiayaLain;

                Transaction::create([
                    'invoice_no'         => $invoiceNo,
                    'vehicle_id'         => $vehicleId,
                    'jenis_layanan'      => $trx['jenis_layanan'],
                    'status_proses'      => $trx['status_proses'],
                    'biaya_pajak'        => $pajak,
                    'biaya_jasa'         => $jasa,
                    'loket_pendaftaran'  => $loketPendaftaran,
                    'loket_cek_fisik'    => $loketCekFisik,
                    'acc_tidak_hadir'    => $accTidakHadir,
                    'acc_domisili'       => $accDomisili,
                    'loket_penetapan'    => $loketPenetapan,
                    'loket_pengesahan_1' => $loketPengesahan1,
                    'loket_pengesahan_2' => $loketPengesahan2,
                    'bea_materai'        => $beaMaterai,
                    'biaya_lain'         => $grandTotalBiayaLain,
                    'total_biaya'        => $grandTotal,
                    'tgl_masuk'          => $tglMasuk,
                    'tgl_selesai'        => $tglSelesai,
                ]);
            }
        }

        return redirect()->route('transactions.index')->with('success', 'Transaksi baru berhasil ditambahkan dengan Nomor: ' . $invoiceNo);

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
            ->where('invoice_no', 'like', $baseInvoice . '%')
            ->get();

        // 4. Ambil data klien dari transaksi pertama
        $client = $transactions->first()->vehicle->client ?? null;
        // daftar status proses yang bisa dipilih
        $statuses = [
            'Pending', 'Done', 'Cancel'
        ];
        return view('transactions.edit', compact('transactions', 'baseInvoice', 'client', 'statuses'));
    }

    // perbarui status pengurusan biro jasa
    public function update(Request $request, string $id)
    {
        // Ambil array transaksi dan tanggal dari input global form
        $dataTransaksi = $request->input('transaksi');
        $tglMasukGlobal = $request->input('tgl_masuk');
        $tglSelesaiGlobal = $request->input('tgl_selesai');

        // Lakukan perulangan untuk mengupdate SETIAP kendaraan dalam invoice kolektif ini
        foreach ($dataTransaksi as $trxId => $data) {
            $transaction = Transaction::find($trxId);
            if ($transaction) {
                // 1. Simpan status lama sebelum diupdate untuk perbandingan
                $statusLama = $transaction->status_proses;
                $statusBaru = $data['status_proses'];

                // Atur tanggal selesai secara cerdas per kendaraan
                $tglSelesai = $tglSelesaiGlobal;
                if ($statusBaru == 'Done' && empty($tglSelesai)) {
                    $tglSelesai = date('Y-m-d'); // Auto-fill hari ini jika Done tapi form tgl_selesai kosong
                } elseif ($statusBaru == 'Cancel') {
                    $tglSelesai = null; // Kosongkan jika dibatalkan
                }

                // Kalkulasi Biaya (Konversi ke integer agar aman)
                $pajak = (int) ($data['biaya_pajak'] ?? 0);
                $jasa  = (int) ($data['biaya_jasa'] ?? 0);
                
                $loketPendaftaran = (int) ($data['loket_pendaftaran'] ?? 0);
                $loketCekFisik    = (int) ($data['loket_cek_fisik'] ?? 0);
                $accTidakHadir    = (int) ($data['acc_tidak_hadir'] ?? 0);
                $accDomisili      = (int) ($data['acc_domisili'] ?? 0);
                $loketPenetapan   = (int) ($data['loket_penetapan'] ?? 0);
                $loketPengesahan1 = (int) ($data['loket_pengesahan_1'] ?? 0);
                $loketPengesahan2 = (int) ($data['loket_pengesahan_2'] ?? 0);
                $beaMaterai       = (int) ($data['bea_materai'] ?? 0);

                $grandTotalBiayaLain = $loketPendaftaran + $loketCekFisik + $accTidakHadir + $accDomisili +
                                       $loketPenetapan + $loketPengesahan1 + $loketPengesahan2 + $beaMaterai;

                $grandTotal = $pajak + $jasa + $grandTotalBiayaLain;

                // 2. Update data transaksi
                $transaction->update([
                    'jenis_layanan'      => $data['jenis_layanan'],
                    'status_proses'      => $statusBaru,
                    'biaya_pajak'        => $pajak,
                    'biaya_jasa'         => $jasa,
                    // 8 rincian biaya lain
                    'loket_pendaftaran'  => $loketPendaftaran,
                    'loket_cek_fisik'    => $loketCekFisik,
                    'acc_tidak_hadir'    => $accTidakHadir,
                    'acc_domisili'       => $accDomisili,
                    'loket_penetapan'    => $loketPenetapan,
                    'loket_pengesahan_1' => $loketPengesahan1,
                    'loket_pengesahan_2' => $loketPengesahan2,
                    'bea_materai'        => $beaMaterai,
                    // grand total
                    'biaya_lain'         => $grandTotalBiayaLain,
                    'total_biaya'        => $grandTotal,
                    'tgl_masuk'          => $tglMasukGlobal,
                    'tgl_selesai'        => $tglSelesai,
                ]);

                // 3. FITUR OTOMATISASI PERPANJANGAN PAJAK (AUTO-RENEW)
                // Sistem hanya akan menambah tahun JIKA statusnya BARU SAJA berubah menjadi 'Done'
                // (Ini mencegah penambahan tahun berkali-kali jika mengedit transaksi yang sudah Done)
                if ($statusLama != 'Done' && $statusBaru == 'Done') {
                    // Cari data STNK milik kendaraan ini
                    $stnk = STNKRecord::where('vehicle_id', $transaction->vehicle_id)->first();

                    if ($stnk) {
                        // A. Pajak Tahunan
                        if ($transaction->jenis_layanan == 'Pajak Tahunan' && $stnk->tgl_jatuh_tempo_pajak) {
                            // Tambah 1 tahun
                            $stnk->tgl_jatuh_tempo_pajak = Carbon::parse($stnk->tgl_jatuh_tempo_pajak)->addYear();
                        } 
                        elseif ($transaction->jenis_layanan == 'Pajak 5 Tahunan') { //B. Ganti Kaleng 5 Tahunan
                            if ($stnk->tgl_jatuh_tempo_pajak) {
                                // Maju 1 tahun untuk pajak tahunan
                                $stnk->tgl_jatuh_tempo_pajak = Carbon::parse($stnk->tgl_jatuh_tempo_pajak)->addYear();
                            }
                            if ($stnk->tgl_habis_stnk) {
                                // Plat nomor maju 5 tahun
                                $stnk->tgl_habis_stnk = Carbon::parse($stnk->tgl_habis_stnk)->addYear(5);
                            }
                        }
                        $stnk->save();
                    }
                }

                // 4. FITUR PENGURANGAN PAJAK KETIKA STATUS MENJADI CANCEL
                if ($statusLama == 'Done' && $statusBaru == 'Cancel') {
                    // Cari data STNK milik kendaraan ini
                    $stnk = STNKRecord::where('vehicle_id', $transaction->vehicle_id)->first();

                    if ($stnk) {
                        // A. Pajak Tahunan
                        if ($transaction->jenis_layanan == 'Pajak Tahunan' && $stnk->tgl_jatuh_tempo_pajak) {
                            // Kurangi 1 tahun
                            $stnk->tgl_jatuh_tempo_pajak = Carbon::parse($stnk->tgl_jatuh_tempo_pajak)->subYear();
                        } 
                        elseif ($transaction->jenis_layanan == 'Pajak 5 Tahunan') { //B. Ganti Kaleng 5 Tahunan
                            if ($stnk->tgl_jatuh_tempo_pajak) {
                                // Kurangi 1 tahun untuk pajak tahunan
                                $stnk->tgl_jatuh_tempo_pajak = Carbon::parse($stnk->tgl_jatuh_tempo_pajak)->subYear();
                            }
                            if ($stnk->tgl_habis_stnk) {
                                // Plat nomor berkurang 5 tahun
                                $stnk->tgl_habis_stnk = Carbon::parse($stnk->tgl_habis_stnk)->subYear(5);
                            }
                        }
                        $stnk->save();
                    }
                    $transaction->update(['tgl_selesai' => null]);
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
