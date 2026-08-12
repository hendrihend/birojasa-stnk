@extends('layouts.app')

@section('title', 'Tambah Kendaraan')
@section('header_title', 'Tambah Data Kendaraan Baru')

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

        <form action="{{ route('vehicles.store') }}" method="POST">
            @csrf
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Dropdown Klien Memakan 2 Kolom Penuh -->
                <div class="col-span-1 md:col-span-2">
                    <label class="block text-sm font-bold text-gray-700 mb-1">Pilih Pemilik / Klien <span class="text-red-500">*</span></label>
                    <select name="client_id" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-shadow bg-gray-50 focus:bg-white appearance-none">
                        <option value="">-- Pilih Klien --</option>
                        @foreach($clients as $client)
                            <option value="{{ $client->id }}" {{ old('client_id') == $client->id ? 'selected' : '' }}>
                                {{ $client->nama_lengkap }} ({{ $client->no_whatsapp }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-1">Nomor Polisi <span class="text-red-500">*</span></label>
                    <input type="text" name="nopol" value="{{ old('nopol') }}" required placeholder="Contoh: B 1234 ABC" 
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-shadow bg-gray-50 focus:bg-white uppercase">
                </div>

                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-1">Nomor Rangka <span class="text-red-500">*</span></label>
                    <input type="text" name="no_rangka" value="{{ old('no_rangka') }}" required placeholder="Masukkan nomor rangka" 
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-shadow bg-gray-50 focus:bg-white">
                </div>

                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-1">Nomor Mesin</label>
                    <input type="text" name="no_mesin" value="{{ old('no_mesin') }}" placeholder="Masukkan no mesin" 
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-shadow bg-gray-50 focus:bg-white">
                </div>

                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-1">Merk Kendaraan</label>
                    <input type="text" name="merk" value="{{ old('merk') }}" placeholder="Contoh: Honda, Toyota" 
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-shadow bg-gray-50 focus:bg-white">
                </div>

                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-1">Tipe Kendaraan</label>
                    <input type="text" name="tipe" value="{{ old('tipe') }}" placeholder="Contoh: Vario 150, Avanza" 
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-shadow bg-gray-50 focus:bg-white">
                </div>

                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-1">Tahun Pembuatan</label>
                    <input type="number" name="tahun_pembuatan" value="{{ old('tahun_pembuatan') }}" placeholder="Contoh: 2020" min="1950" max="{{ date('Y') + 1 }}"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-shadow bg-gray-50 focus:bg-white">
                </div>

                <div class="col-span-1 md:col-span-2">
                    <label class="block text-sm font-bold text-gray-700 mb-1">Warna Kendaraan</label>
                    <input type="text" name="warna" value="{{ old('warna') }}" placeholder="Contoh: Biru" min="1950" max="{{ date('Y') + 1 }}"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-shadow bg-gray-50 focus:bg-white">
                </div>

                <!-- Nama di STNK Memakan 2 Kolom Penuh -->
                <div class="col-span-1 md:col-span-2">
                    <label class="block text-sm font-bold text-gray-700 mb-1">Nama Tercetak di STNK</label>
                    <input type="text" name="nama_pemilik" value="{{ old('nama_pemilik') }}" placeholder="Kosongkan jika namanya sama dengan nama Klien" 
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-shadow bg-gray-50 focus:bg-white">
                    <p class="text-xs text-gray-500 mt-1">Sistem otomatis menggunakan nama Klien jika dikosongkan.</p>
                </div>
            </div>

            <div class="mt-8 flex gap-3">
                <button type="submit" class="px-6 py-2.5 bg-gray-900 hover:bg-gray-800 text-white font-bold rounded-lg shadow-sm transition-colors">
                    Simpan Kendaraan
                </button>
                <a href="{{ route('vehicles.index') }}" class="px-6 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 font-bold rounded-lg transition-colors">
                    Batal
                </a>
            </div>
        </form>
    </div>
@endsection
