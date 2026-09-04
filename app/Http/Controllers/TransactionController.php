<?php

namespace App\Http\Controllers;

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

    public function create()
    {
        // ambil data kendaraan beserta relasi pemiliknya untuk ditampilkan di dropdown
        $vehicles = Vehicle::with('client')->orderBy('nopol', 'asc')->get();
        return view('transactions.create', compact('vehicles'));
    }

    // simpan transaksi baru dan buat invoice otomatis
    public function store(Request $request)
    {
        // Validasi Input Transaksi
        $request->validate([
            'vehicle_id' => 'required|exists:vehicles,id',
            'jenis_layanan' => 'required|string|max:50',
            'status_proses' => 'required|string',
            'biaya_pajak' => 'required|numeric',
            'biaya_jasa' => 'required|numeric',
            'loket_pendaftaran' => 'nullable|numeric',
            'loket_cek_fisik' => 'nullable|numeric',
            'acc_tidak_hadir' => 'nullable|numeric',
            'acc_domisili' => 'nullable|numeric',
            'loket_penetapan' => 'nullable|numeric',
            'loket_pengesahan_1' => 'nullable|numeric',
            'loket_pengesahan_2' => 'nullable|numeric',
            'bea_materai' => 'nullable|numeric',
            'biaya_lain' => 'nullable|numeric',
            'tgl_masuk' => 'required|date',
            'tgl_selesai' => 'nullable|date|after_or_equal:tgl_masuk',
            ]);

        // VALIDASI DOKUMEN DINAMIS
        // Pemetaan Dokumen Wajib untuk tiap jenis layanan
        $syaratDokumen = [
            'Pajak Tahunan'    => ['STNK', 'KTP'],
            'Pajak 5 Tahunan'  => ['STNK', 'KTP', 'BPKB', 'Hasil Cek Fisik Kendaraan'],
            'Balik Nama'       => ['STNK', 'KTP Pemilik Baru', 'BPKB', 'Kwitansi Jual Beli'],
            'Mutasi Keluar'    => ['STNK', 'KTP', 'BPKB Asli', 'Hasil Cek Fisik Kendaraan'],
        ];
        
        $layananDipilih = $request->jenis_layanan;
        // Tentukan dokumen yg wajib ada sebelum transaksi dilakukan
        $dokumenWajib = $syaratDokumen[$layananDipilih] ?? ['KTP', 'STNK']; // nama harus sama dengan value di input form upload

        $vehicle = Vehicle::with('documents')->find($request->vehicle_id);

        // Ambil daftar dokumen yg sudah diunggah kendaraan ini
        $dokumenTersedia = $vehicle->documents->pluck('jenis_dokumen')->toArray();

        // Cek dokumen wajib yg belum diunggah
        $dokumenKurang = array_diff($dokumenWajib, $dokumenTersedia);
        if (!empty($dokumenKurang)) {
            // Jika dokumen kurang, batalkan transaksi dan kembalikan he halaman form dengan pesan error
            $pesan = 'Transaksi ' . $layananDipilih .' gagal diproses! Dokumen kendaraan belum lengkap. Wajib melampirkan: ' . implode(', ', $dokumenKurang);
            return redirect()->back()->withInput()->with('error', $pesan);
        }

            $invoiceNo = 'INV-' . date('Ymd') . '-' . rand(1000, 9999);
            // Hitung grand total biaya lain
            $loketPendaftaran = (int) $request->loket_pendaftaran;
            $loketCekFisik = (int) $request->loket_cek_fisik;
            $accTidakHadir = (int) $request->acc_tidak_hadir;
            $accDomisili = (int) $request->acc_domisili;
            $loketPenetapan = (int) $request->loket_penetapan;
            $loketPengesahan1 = (int) $request->loket_pengesahan_1;
            $loketPengesahan2 = (int) $request->loket_pengesahan_2;
            $beaMaterai = (int) $request->bea_materai;
            $grandTotalBiayaLain = $loketPendaftaran + $loketCekFisik + $accTidakHadir + $accDomisili +
                                    $loketPenetapan + $loketPengesahan1 + $loketPengesahan2 + $beaMaterai;

            // Hitung grand total biaya otomatis (int) supaya aman jika dikosongkan
            $pajak = (int) $request->biaya_pajak;
            $jasa = (int) $request->biaya_jasa;
            $grandTotal = $pajak + $jasa + $grandTotalBiayaLain;
            Transaction::create([
                'invoice_no' =>$invoiceNo,
                'vehicle_id' => $request->vehicle_id,
                'jenis_layanan' => $request->jenis_layanan,
                'status_proses' => $request->status_proses,
                'biaya_pajak' => $request->biaya_pajak,
                'biaya_jasa' => $request->biaya_jasa,
                // 8 rincian biaya lain
                'loket_pendaftaran' => $loketPendaftaran,
                'loket_cek_fisik' => $loketCekFisik,
                'acc_tidak_hadir' => $accTidakHadir,
                'acc_domisili' => $accDomisili,
                'loket_penetapan' => $loketPenetapan,
                'loket_pengesahan_1' => $loketPengesahan1,
                'loket_pengesahan_2' => $loketPengesahan2,
                'bea_materai' => $beaMaterai,
                // grand total
                'biaya_lain' => $grandTotalBiayaLain,
                'total_biaya' => $grandTotal,
                'tgl_masuk' => $request->tgl_masuk,
                'tgl_selesai' => $request->tgl_selesai,
            ]);

            return redirect()->route('transactions.index')->with('success', 'Transaksi baru berhasil ditambahkan dengan Nomor: ' . $invoiceNo);

    }

    public function show(string $id)
    {
        //
    }

    public function edit(string $id)
    {
        $transaction = Transaction::findOrFail($id);
        // daftar status proses yang bisa dipilih
        $statuses = [
            'Pending', 'Done', 'Cancel'
        ];
        return view('transactions.edit', compact('transaction', 'statuses'));
    }

    // perbarui status pengurusan biro jasa
    public function update(Request $request, string $id)
    {
        $request->validate([
            'jenis_layanan' => 'required|string',
            'status_proses' => 'required|string',
            'biaya_pajak' => 'required|numeric',
            'biaya_jasa' => 'nullable|numeric',
            'loket_pendaftaran' => 'nullable|numeric',
            'loket_cek_fisik' => 'nullable|numeric',
            'acc_tidak_hadir' => 'nullable|numeric',
            'acc_domisili' => 'nullable|numeric',
            'loket_penetapan' => 'nullable|numeric',
            'loket_pengesahan_1' => 'nullable|numeric',
            'loket_pengesahan_2' => 'nullable|numeric',
            'bea_materai' => 'nullable|numeric',
            'biaya_lain' => 'nullable|numeric',
            'tgl_selesai' => 'nullable|date|after_or_equal:tgl_masuk',
        ]);
        $transaction = Transaction::findOrFail($id);

        // 1. Simpan status lama sebelum diupdate untuk perbandingan
        $statusLama = $transaction->status_proses;
        $statusBaru = $request->status_proses;

        // jika status diubah ke Done, dan tgl selesai kosong, otomatis isi tgl hari ini
        $tglSelesai = $request->tgl_selesai;
        if ($request->status_proses == 'Done' && empty($tglSelesai)) {
            $tglSelesai = date('Y-m-d');
        }

        // Hitung grand total biaya lain
        $loketPendaftaran = (int) $request->loket_pendaftaran;
        $loketCekFisik = (int) $request->loket_cek_fisik;
        $accTidakHadir = (int) $request->acc_tidak_hadir;
        $accDomisili = (int) $request->acc_domisili;
        $loketPenetapan = (int) $request->loket_penetapan;
        $loketPengesahan1 = (int) $request->loket_pengesahan_1;
        $loketPengesahan2 = (int) $request->loket_pengesahan_2;
        $beaMaterai = (int) $request->bea_materai;
        $grandTotalBiayaLain = $loketPendaftaran + $loketCekFisik + $accTidakHadir + $accDomisili +
                                $loketPenetapan + $loketPengesahan1 + $loketPengesahan2 + $beaMaterai;

        // Hitung grand total biaya otomatis (int) supaya aman jika dikosongkan
        $pajak = (int) $request->biaya_pajak;
        $jasa = (int) $request->biaya_jasa;
        $grandTotal = $pajak + $jasa + $grandTotalBiayaLain;

        // 2. Update data transaksi
        $transaction->update([
            'jenis_layanan' => $request->jenis_layanan,
            'status_proses' => $statusBaru,
            'biaya_pajak' => $pajak,
            'biaya_jasa' => $jasa,
            // 8 rincian biaya lain
            'loket_pendaftaran' => $loketPendaftaran,
            'loket_cek_fisik' => $loketCekFisik,
            'acc_tidak_hadir' => $accTidakHadir,
            'acc_domisili' => $accDomisili,
            'loket_penetapan' => $loketPenetapan,
            'loket_pengesahan_1' => $loketPengesahan1,
            'loket_pengesahan_2' => $loketPengesahan2,
            'bea_materai' => $beaMaterai,
            // grand total
            'biaya_lain' => $grandTotalBiayaLain,
            'total_biaya' => $grandTotal, // Hasil penjumlahan
            'tgl_selesai' => $tglSelesai,
        ]);

        // 3. FITUR OTOMATISASI PERPANJANGAN PAJAK (AUTO-RENEW)
        // =========================================================
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
        // Ambil data transaksi beserta relasi kendaraan dan klien
        $transaction = Transaction::with(['vehicle.client'])->findOrFail($id);
        return view('transactions.print', compact('transaction'));
    }

    public function bulkPrint(Request $request) 
    {
        $ids = $request->input('ids'); // Menangkap array ID yg dicentang
        if (!$ids || count($ids) == 0) {
            return redirect()->back()->with('error', 'Pilih minimal satu transaksi untuk dicetak.');
        }

        // Ambil data semua transaksi yg dipilih
        $transactions = Transaction::with(['vehicle.client'])->whereIn('id', $ids)->get();

        // Validasi: pastikan semua transaksi milik klien yg sama
        $klienPertama = $transactions->first()->vehicle->client_id;
        foreach ($transactions as $trx) {
            if ($trx->vehicle->client_id != $klienPertama) {
                return redirect()->back()->with('error', 'Gagal! Transaksi yang digabung harus dengan Klien yang sama.');
            }
        }
        // Ambil data klien untuk ditaruh di kop surat
        $client = $transactions->first()->vehicle->client;

        // Buat nomor invoice gabungan baru (Contoh: INV-KOL/08/2026/1234)
        $invoiceNo = 'INV-KOL/' . date('m/Y/') . rand(1000, 9999);

        // Hitung Grand Total Semua transaksi
        $grandTotalKol = $transactions->sum('total_biaya');

        // Hitung total gabungan biaya lain untuk cetak massal
        $totalBiayaLain = $transactions->sum('biaya_lain');
        $totalPendaftaran = $transactions->sum('loket_pendaftaran');
        $totalCekFisik = $transactions->sum('loket_cek_fisik');
        $totalAccTidakHadir = $transactions->sum('acc_tidak_hadir');
        $totalAccDomisili = $transactions->sum('acc_domisili');
        $totalLoketPenetapan = $transactions->sum('loket_penetapan');
        $totalLoketPengesahan1 = $transactions->sum('loket_pengesahan_1');
        $totalLoketPengesahan2 = $transactions->sum('loket_pengesahan_2');
        $totalBeaMaterai = $transactions->sum('bea_materai');


        return view('transactions.print-bulk', compact(
            'transactions', 'client', 'invoiceNo', 'grandTotalKol',
            'totalBiayaLain', 'totalPendaftaran', 'totalCekFisik', 'totalAccTidakHadir', 'totalAccDomisili',
            'totalLoketPenetapan', 'totalLoketPengesahan1', 'totalLoketPengesahan2', 'totalBeaMaterai'
        ));

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
