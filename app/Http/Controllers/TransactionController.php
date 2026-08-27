<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\Vehicle;
use App\Models\STNKRecord;
use Carbon\Carbon;
use Illuminate\Http\Request;

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
            })->latest()->get();
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
        $request->validate([
            'vehicle_id' => 'required|exists:vehicles,id',
            'jenis_layanan' => 'required|string|max:50',
            'status_proses' => 'required|string',
            'biaya_pajak' => 'required|numeric',
            'biaya_jasa' => 'required|numeric',
            'biaya_lain' => 'required|numeric',
            'tgl_masuk' => 'required|date',
            'tgl_selesai' => 'nullable|date|after_or_equal:tgl_masuk',
            ]);

            $invoiceNo = 'INV-' . date('Ymd') . '-' . rand(1000, 9999);
            // Hitung total biaya otomatis di sistem
            $total = $request->biaya_pajak + $request->biaya_jasa + $request->biaya_lain;
            Transaction::create([
                'invoice_no' =>$invoiceNo,
                'vehicle_id' => $request->vehicle_id,
                'jenis_layanan' => $request->jenis_layanan,
                'status_proses' => $request->status_proses,
                'biaya_pajak' => $request->biaya_pajak,
                'biaya_jasa' => $request->biaya_jasa,
                'biaya_lain' => $request->biaya_lain,
                'total_biaya' => $total,
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

        // Hitung total biaya otomatis (int) supaya aman jika dikosongkan
        $pajak = (int) $request->biaya_pajak;
        $jasa = (int) $request->biaya_jasa;
        $lain = (int) $request->biaya_lain;
        $total = $pajak + $jasa + $lain;

        // 2. Update data transaksi
        $transaction->update([
            'jenis_layanan' => $request->jenis_layanan,
            'status_proses' => $statusBaru,
            'biaya_pajak' => $pajak,
            'biaya_jasa' => $jasa,
            'biaya_lain' => $lain,
            'total_biaya' => $total, // Hasil penjumlahan
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

        return redirect()->route('transactions.index')->with('success', 'Transaksi berhasil diperbarui! Jika status Selesai (Done), masa aktif STNK telah diperpanjang otomatis.');
    }

    public function destroy(string $id)
    {
        $transaction = Transaction::findOrFail($id);
        $transaction->delete();
        return redirect()->route('transactions.index')->with('success', 'Transaksi berhasil dihapus.');
    }

    public function print($id) {
        // Ambil data transaksi beserta relasi kendaraan dan klien
        $transaction = Transaction::with(['vehicle.client'])->findOrFail($id);
        return view('transactions.print', compact('transaction'));
    }




}
