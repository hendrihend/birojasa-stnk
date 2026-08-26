<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\Vehicle;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    public function index()
    {
        // ambil data transaksi beserta relasi kendaraan & pemiliknya
        $transactions = Transaction::with('vehicle.client')->latest()->get();
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
            'total_biaya' => 'required|numeric|min:0',
            'tgl_masuk' => 'required|date',
            'tgl_selesai' => 'nullable|date|after_or_equal:tgl_masuk',
            ]);

            // generate invoice number otomatis dengan format INV-YYYYMMDD-XXXX
            $invoiceNo = 'INV-' . date('Ymd') . '-' . rand(1000, 9999);
            Transaction::create([
                'invoice_no' => $invoiceNo,
                'vehicle_id' => $request->vehicle_id,
                'jenis_layanan' => $request->jenis_layanan,
                'total_biaya' => $request->total_biaya,
                'status_proses' => 'Pending', // default status proses
                'tgl_masuk' => $request->tgl_masuk,
                'tgl_selesai' => $request->tgl_selesai,
            ]);

            return redirect()->route('transactions.index')->with('success', 'Transaksi berhasil dibuat dengan nomor invoice: ' . $invoiceNo);

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
            'Belum Diproses', 'Dokumen Lengkap', 'Menunggu Pembayaran',
            'Sedang Diproses Samsat', 'Proses Mutasi', 'STNK Sudah Jadi',
            'Plat Nomor Sudah Jadi', 'Dokumen Sudah Diterima', 'Selesai'
        ];
        return view('transactions.edit', compact('transaction', 'statuses'));
    }

    // perbarui status pengurusan biro jasa
    public function update(Request $request, string $id)
    {
        $request->validate([
            'status_proses' => 'required|string',
            'tgl_selesai' => 'nullable|date|after_or_equal:tgl_masuk',
            'total_biaya' => 'required|numeric|min:0',
        ]);
        $transaction = Transaction::findOrFail($id);
        // jika status diubah ke Selesai, dan tgl selesai kosong, otomatis isi tgl hari ini
        $tglSelesai = $request->tgl_selesai;
        if ($request->status_proses == 'selesai' && empty($tglSelesai)) {
            $tglSelesai = date('Y-m-d');
        }
        $transaction->update([
            'status_proses' => $request->status_proses,
            'tgl_selesai' => $tglSelesai,
            'total_biaya' => $request->total_biaya,
        ]);
        return redirect()->route('transactions.index')->with('success', 'Status pengurusan dan data transaksi berhasil diperbarui.');
    }

    public function destroy(string $id)
    {
        $transaction = Transaction::findOrFail($id);
        $transaction->delete();
        return redirect()->route('transactions.index')->with('success', 'Transaksi berhasil dihapus.');
    }
}
