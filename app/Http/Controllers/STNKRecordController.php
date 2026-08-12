<?php

namespace App\Http\Controllers;

use App\Models\STNKRecord;
use App\Models\Vehicle;
use Illuminate\Http\Request;

class STNKRecordController extends Controller
{

    public function index(Request $request)
    {
        $search = $request->search;

        // Cari berdasarkan No STNK atau Nopol
        $records = STNKRecord::with('vehicle.client')
            ->when($search, function ($query, $search) {
                return $query->where('no_stnk', 'like', "%{$search}%")
                             ->orWhereHas('vehicle', function($q) use ($search) {
                                 $q->where('nopol', 'like', "%{$search}%")
                                   ->orWhereHas('client', function($q2) use ($search) {
                                       $q2->where('nama_lengkap', 'like', "%{$search}%");
                                   });
                             });
            })->orderBy('tgl_jatuh_tempo_pajak', 'asc')->get();
        return view('stnk_records.index', compact('records'));
    }

    public function create()
    {
        // ambil semua kendaraan untuk pilihan dropdown
        $vehicles = Vehicle::with('client')->get();
        return view('stnk_records.create', compact('vehicles'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'vehicle_id' => 'required|exists:vehicles,id',
            'no_stnk' => 'required|string|max:50',
            'tgl_jatuh_tempo_pajak' => 'required|date',
            'tgl_habis_stnk' => 'required|date',
            'status_aktif' => 'required|boolean',
        ]);


        // Jika STNK baru ini diset aktif, nonaktifkan STNK lama untuk kendaraan ini
        if ($request->status_aktif) {
            STNKRecord::where('vehicle_id', $request->vehicle_id)->update(['status_aktif' => false]);
        }
        STNKRecord::create($request->all());
        return redirect()->route('stnk_records.index')->with('success', 'Data STNK / Pajak berhasil ditambahkan!');
    }

    public function show(string $id)
    {
        //
    }

    public function edit(string $id)
    {
        //
    }

    public function update(Request $request, string $id)
    {
        //
    }

    public function destroy(string $id)
    {
        STNKRecord::findOrFail($id)->delete();
        return redirect()->route('stnk-records.index')->with('success', 'Data STNK berhasil dihapus.');
    }
}
