<?php

namespace App\Http\Controllers;
use App\Models\Vehicle;
use App\Models\Client;
use Illuminate\Http\Request;

class VehicleController extends Controller
{
    public function index()
    {
        // ambil semua data kendaraan beserta data klien terkait
        $vehicles = Vehicle::with('client')->latest()->get();
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
        // validasi input
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
        ]);

        // tampung input ke variabel data
        $data = $request->all();

        // jika nama pemilik kosong
        if (empty($data['nama_pemilik'])){
            // cari data klien berdasarkan client_id yg dipilih
            $client = Client::find($data['client_id']);
            // nama pemilik akan terisi otomatis dengan nama lengkap klien tersebut
            $data['nama_pemilik'] = $client->nama_lengkap;
        }

        // simpan data kendaraan
        Vehicle::create($data);
        return redirect()->route('vehicles.index')->with('success', 'Data kendaraan berhasil ditambahkan.');
    }

    public function show(string $id)
    {
        //
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
