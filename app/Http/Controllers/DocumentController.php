<?php

namespace App\Http\Controllers;

use App\Models\Document;
use App\Models\Vehicle;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class DocumentController extends Controller
{
    // tampilkan halaman daftar dokumen untuk kendaraan tertentu
    public function index($vehicle_id)
    {
        $vehicle = Vehicle::with('client')->findOrFail($vehicle_id);
        // ambil dokumen milik kendaraan tersebut
        $documents = Document::where('vehicle_id', $vehicle_id)->get();
        return view('documents.index', compact('vehicle', 'documents'));
    }

    public function store(Request $request, $vehicle_id)
    {
        // pesan kustom sebagai parameter ketika dokumen tidak sesuai dengan format yang diizinkan
        $messages = [
            'file_dokumen.required' => 'Anda belum memilih file dokumen.',
            'file_dokumen.mimes' => 'Gagal! Format file dokumen harus berupa JPG, JPEG, PNG, atau PDF.',
            'file_dokumen.max' => 'Gagal! Ukuran file dokumen terlalu besar. Maksimal 2MB.',
            'jenis_dokumen.required' => 'Anda harus memilih jenis dokumen terlebih dahulu.',
        ];


        // validasi input
        $request->validate([
            'jenis_dokumen' => 'required|in:KTP,STNK,BPKB,Faktur,Kwitansi,Surat Jalan',
            'file_dokumen' => 'required|file|mimes:jpg,jpeg,png,pdf|max:2048', // Maksimal 2MB, format gambar/PDF

        ], $messages);
        $vehicle = Vehicle::findOrFail($vehicle_id);
        // proses simpan file
        if ($request->hasFile('file_dokumen')) {
            $file = $request->file('file_dokumen');
            // buat nama file unik
            $nopol_clean = str_replace(' ', '', $vehicle->nopol); //hilangkan spasi
            $filename = $request->jenis_dokumen . '_' . $nopol_clean . '_' . time() . '.' . $file->getClientOriginalExtension();
            // simpan file ke folder storage/app/public/dokumen_kendaraan
            $path = $file->storeAs('dokumen_kendaraan', $filename, 'public');

            // simpan data path ke database
            Document::create([
                'vehicle_id' => $vehicle_id,
                'jenis_dokumen' => $request->jenis_dokumen,
                'file_path' => $path,
            ]);
            return redirect()->back()->with('success', 'Dokumen ' . $request->jenis_dokumen . ' berhasil diunggah.');
        }
        return redirect()->back()->withErrors('Gagal mengunggah dokumen.');
    }

    public function destroy($id)
    {
        $document = Document::findOrFail($id);
        // hapus file dari storage
        if (Storage::disk('public')->exists($document->file_path)) {
            Storage::disk('public')->delete($document->file_path);
        }
        // hapus data dari database
        $document->delete();
        return redirect()->back()->with('success', 'Dokumen berhasil dihapus.');
    }



}
