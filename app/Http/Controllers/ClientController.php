<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Client;

class ClientController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->search;
        // cari berdasarkan nam, nik dan no whatsapp
        $clients = Client::when($search, function($query, $search) {
            return $query->where('nama_lengkap', 'like', "%{$search}%")
                        ->orWhere('nik', 'like', "%{$search}%")
                        ->orWhere('no_whatsapp', 'like', "%{$search}%");
        })->latest()->get();
        return view('clients.index', compact('clients'));
    }

    public function create()
    {
        return view('clients.create');
    }

    public function store(Request $request)
    {
        // validasi input
        $request->validate([
            'nik' => 'nullable|unique:clients,nik|max:16',
            'nama_lengkap' => 'required|string|max:255',
            'no_whatsapp' => 'nullable|string|max:20',
            'alamat' => 'nullable|string|max:500',
        ]);

        // simpan data client baru ke database menggunakan mass assignment
        Client::create($request->all());
        // redirect ke halaman index dengan pesan sukses
        return redirect()->route('clients.index')->with('success', 'Data client berhasil ditambahkan.');
    }

    public function show(string $id)
    {
        //
    }

    public function edit(string $id)
    {
        //Cari data klien berdasarkan id, jika tidak ditemukan maka akan menampilkan halaman 404
        $client = Client::findOrFail($id);
        return view('clients.edit', compact('client'));
    }

    public function update(Request $request, string $id)
    {
        //Validasi input
        $request->validate([
            'nik' => 'nullable|unique:clients,nik,' . $id . '|max:16',
            'nama_lengkap' => 'required|string|max:255',
            'no_whatsapp' => 'nullable|string|max:20',
            'alamat' => 'nullable|string|max:500',
        ]);

        //Cari data klien berdasarkan id, jika tidak ditemukan maka akan menampilkan halaman 404
        $client = Client::findOrFail($id);
        //Update data klien
        $client->update($request->all());
        //Redirect ke halaman index dengan pesan sukses
        return redirect()->route('clients.index')->with('success', 'Data client ' . $client->nama_lengkap .' berhasil diperbarui.');
    }

    public function destroy(string $id)
    {
        $client = Client::findOrFail($id);
        $client->delete();
        return redirect()->route('clients.index')->with('success', 'Data client berhasil dihapus.');
    }
}
