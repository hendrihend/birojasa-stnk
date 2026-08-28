<?php

namespace App\Http\Controllers;
use App\Models\Client;
use App\Models\Vehicle;
use App\Models\STNKRecord;
use App\Models\Document;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class VehicleController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->search;

        $vehicles = Vehicle::with('client')
        ->when($search, function ($query, $search) {
            return $query->where(function ($q) use ($search) {
                $q->where('nopol', 'like', "%{$search}%")
                  ->orWhere('merk', 'like', "%{$search}%")
                  ->orWhere('nama_pemilik', 'like', "%{$search}%")
                  ->orWhereHas('client', function ($q) use ($search) {
                      $q->where('nama_lengkap', 'like', "%{$search}%");
                  });
            });
        })
        ->latest()
        ->paginate(10)
        ->withQueryString();

    return view('vehicles.index', compact('vehicles'));
}

    public function create()
    {
        // ambil semua data klien untuk dropdown
        $clients = Client::orderBy('nama_lengkap', 'asc')->get();
        return view('vehicles.create', compact('clients'));
    }

    public function store(Request $request)
    {
        // pesan kustom sebagai parameter ketika dokumen tidak sesuai dengan format yang diizinkan
        $messages = [
            'file_dokumen.mimes' => 'Gagal! Format file dokumen harus berupa JPG, JPEG, PNG, atau PDF.',
            'file_dokumen.max' => 'Gagal! Ukuran file dokumen terlalu besar. Maksimal 2MB.',
        ];

        // validasi input gabuungan
        $request->validate([
            'client_id' => 'required|exists:clients,id',
            'nopol' => 'required|string|max:15|unique:vehicles,nopol',
            'no_rangka' => 'required|string|max:50|unique:vehicles,no_rangka',
            'no_mesin' => 'required|string|max:50|unique:vehicles,no_mesin',
            'merk' => 'required|string|max:50',
            'tipe' => 'required|string|max:50',
            'tahun_pembuatan' => 'required|integer|min:1900|max:' . date('Y'),
            'warna' => 'required|string|max:30',
            'nama_pemilik' => 'nullable|string|max:100',

            // validasi input STNK
            'no_stnk' => 'nullable|string',
            'tgl_jatuh_tempo_pajak' => 'nullable|date',
            'tgl_habis_stnk' => 'nullable|date',

            // validasi dokumen
            'jenis_dokumen' => 'in:KTP,STNK,BPKB,Faktur,Kwitansi,Surat Jalan',
            'file_dokumen' => 'file|mimes:jpg,jpeg,png,pdf|max:2048', // Maksimal 2MB, format gambar/PDF

        ]);

        DB::beginTransaction();

        try {
            // jika nama pemilik kosong
            $namaPemilik = $request->nama_pemilik;
            if (empty($namaPemilik)){
                // cari data klien berdasarkan client_id yg dipilih
                $client = Client::find($request['client_id']);
                // nama pemilik akan terisi otomatis dengan nama lengkap klien tersebut
                $namaPemilik = $client->nama_lengkap;
            }

            // simpan kendaraan 
            $vehicle = Vehicle::create([
                'client_id' => $request->client_id,
                'nopol' => strtoupper($request->nopol),
                'no_rangka' => $request->no_rangka,
                'no_mesin' => $request->no_mesin,
                'merk' => $request->merk,
                'tipe' => $request->tipe,
                'tahun_pembuatan' => $request->tahun_pembuatan,
                'warna' => $request->warna,
                'nama_pemilik' => $namaPemilik,
            ]);

            // simpan stnk (jika ada input)
            if ($request->tgl_jatuh_tempo_pajak || $request->tgl_habis_stnk) {
                StnkRecord::create([
                    'vehicle_id' => $vehicle->id,
                    'no_stnk' => $request->no_stnk,
                    'tgl_jatuh_tempo_pajak' => $request->tgl_jatuh_tempo_pajak,
                    'tgl_habis_stnk' => $request->tgl_habis_stnk,
                    'status_aktif' => true,
                ]);
            }

            // simpan dokumen (jika ada file unggah)
            if ($request->hasFile('file_dokumen')) {
                $file = $request->file('file_dokumen');
                
                // Buat nama file yang rapi: NOPOL_JENIS_TIMESTAMP.ext
                $fileName = str_replace(' ', '', $vehicle->nopol) . '_' . 
                            str_replace(' ', '', $request->jenis_dokumen) . '_' . 
                            time() . '.' . $file->getClientOriginalExtension();
                
                // Simpan ke folder 'public/documents' di dalam Storage
                $filePath = $file->storeAs('dokumen_kendaraan', $fileName, 'public');

                Document::create([
                    'vehicle_id' => $vehicle->id,
                    'jenis_dokumen' => $request->jenis_dokumen,
                    'file_path' => $filePath, // Menyimpan lokasi path
                ]);
            }

            
            DB::commit();
    
            return redirect()->route('vehicles.index')->with('success', 'Data kendaraan berhasil ditambahkan.');
        } catch (\Exception $e) {
            DB::rollback(); // Batalkan jika ada yang error
            
            return back()->withInput()->withErrors(['error' => 'Gagal menyimpan data: ' . $e->getMessage()]);
        }


    }

    public function show(string $id)
    {
        $vehicle = Vehicle::with('client')->findOrFail($id);

        // generate URL lengkap halaman ini untuk diubah menjadi QR
        $url_kendaraan = route('vehicles.show', $vehicle->id);
        return view('vehicles.show', compact('vehicle', 'url_kendaraan'));
    }

    public function edit(string $id)
    {
        // cari data kendaraan berdasarkan id, jika tidak ditemukan maka akan menampilkan halaman 404
        $vehicle = Vehicle::findOrFail($id);
        // ambil semua data klien untuk dropdown
        $clients = Client::orderBy('nama_lengkap', 'asc')->get();
        return view('vehicles.edit', compact('vehicle', 'clients'));

    }

    public function update(Request $request, string $id)
    {
        // validasi input
        $request->validate([
            'client_id' => 'required|exists:clients,id',
            'nopol' => 'required|string|max:15|unique:vehicles,nopol,' . $id,
            'no_rangka' => 'required|string|max:50|unique:vehicles,no_rangka,' . $id,
            'no_mesin' => 'required|string|max:50|unique:vehicles,no_mesin,' . $id,
            'merk' => 'required|string|max:50',
            'tipe' => 'required|string|max:50',
            'tahun_pembuatan' => 'required|integer|min:1900|max:' . date('Y'),
            'warna' => 'required|string|max:30',
            'nama_pemilik' => 'nullable|string|max:100',
        ]);

    
        $data = $request->all();

        // Logika yang sama: Jika kosong, ambil nama dari Klien yang dipilih
        if (empty($data['nama_pemilik'])) {
            $client = Client::find($data['client_id']);
            $data['nama_pemilik'] = $client->nama_lengkap;
        }

        $vehicle = Vehicle::findOrFail($id);
        $vehicle->update($data);
        // redirect ke halaman index dengan pesan sukses
        return redirect()->route('vehicles.index')->with('success', 'Data kendaraan berhasil diperbarui.');
    }

    public function destroy(string $id)
    {
        $vehicle = Vehicle::findOrFail($id);
        $vehicle->delete();
        return redirect()->route('vehicles.index')->with('success', 'Data kendaraan berhasil dihapus.');
    }
}
