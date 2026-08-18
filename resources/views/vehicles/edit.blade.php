@extends('layouts.app')
@section('title', 'Edit Kendaraan')
@section('header_title', 'Edit Data Kendaraan')

@section('content')
    <div class="max-w-3xl mx-auto bg-white p-8 rounded-xl shadow-sm border border-gray-100">
        
        @if($errors->any())
            <div class="mb-6 px-4 py-3 bg-red-50 border-l-4 border-red-500 text-red-700 rounded shadow-sm">
                <ul class="list-disc list-inside text-sm font-medium">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('vehicles.update', $vehicle->id) }}" method="POST">
            @csrf
            @method('PUT') <!-- Wajib untuk proses update -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                <div class="col-span-1 md:col-span-2">
                    <label class="block text-sm font-bold text-gray-700 mb-1">Nama Klien *</label>
                    <select name="client_id" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-shadow bg-gray-50 focus:bg-white appearance-none">
                        <option value="">-- Pilih Klien --</option>
                        @foreach($clients as $client)
                            <option value="{{ $client->id }}" {{ (old('client_id', $vehicle->client_id) == $client->id) ? 'selected' : '' }}>
                                {{ $client->nama_lengkap }} (NIK: {{ $client->nik ?? '-' }})
                            </option>
                        @endforeach
                    </select>
                </div>
    
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-1">Nomor Polisi (Nopol) *</label>
                    <input type="text" name="nopol" value="{{ old('nopol', $vehicle->nopol) }}" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-shadow bg-gray-50 focus:bg-white uppercase">
                </div>
    
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-1">No Rangka</label>
                    <input type="text" name="no_rangka" value="{{ old('no_rangka', $vehicle->no_rangka) }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-shadow bg-gray-50 focus:bg-white uppercase">
                </div>
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-1">No Mesin</label>
                    <input type="text" name="no_mesin" value="{{ old('no_mesin', $vehicle->no_mesin) }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-shadow bg-gray-50 focus:bg-white uppercase">
                </div>

                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-1">Merk</label>
                    <input type="text" name="merk" value="{{ old('merk', $vehicle->merk) }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-shadow bg-gray-50 focus:bg-white uppercase">
                </div>
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-1">Tipe</label>
                    <input type="text" name="tipe" value="{{ old('tipe', $vehicle->tipe) }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-shadow bg-gray-50 focus:bg-white uppercase">
                </div>

                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-1">Tahun Pembuatan</label>
                    <input type="text" name="tahun_pembuatan" value="{{ old('tahun_pembuatan', $vehicle->tahun_pembuatan) }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-shadow bg-gray-50 focus:bg-white">
                </div>
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-1">Warna</label>
                    <input type="text" name="warna" value="{{ old('warna', $vehicle->warna) }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-shadow bg-gray-50 focus:bg-white uppercase">
                </div>
    
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-1">Nama Tercetak di STNK</label>
                    <input type="text" name="nama_pemilik" value="{{ old('nama_pemilik', $vehicle->nama_pemilik) }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-shadow bg-gray-50 focus:bg-white uppercase">
                </div>
                
            </div>

            <div class="mt-8 flex gap-3">
                <button type="submit" style="padding: 10px 20px; background: #111; color: #fff; border: none; border-radius: 4px; cursor: pointer;">Update Kendaraan</button>
                <a href="{{ route('vehicles.index') }}" class="px-6 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 font-bold rounded-lg transition-colors">Batal</a>
            </div>

        </form>
    </div>


@endsection