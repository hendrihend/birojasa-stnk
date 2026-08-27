@extends('layouts.app')

@section('title', 'Tambah Data STNK & Pajak')
@section('header_title', 'Tambah Data STNK / Pajak Baru')

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

        <form action="{{ route('stnk_records.store') }}" method="POST">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                <div class="col-span-1 md:col-span-2">
                    <label class="block text-sm font-bold text-gray-700 mb-1">Pilih Kendaraan *</label>
                    <select name="vehicle_id" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-shadow bg-gray-50 focus:bg-white appearance-none">
                        <option value="">-- Pilih Kendaraan --</option>
                        @foreach($vehicles as $vehicle)
                            <option value="{{ $vehicle->id }}" {{ old('vehicle_id') == $vehicle->id ? 'selected' : '' }}>
                                {{ preg_replace('/([A-Z]+)(\d+)([A-Z]+)/', '$1 $2 $3', strtoupper($vehicle->nopol)) }} - {{ $vehicle->client->nama_lengkap ?? 'Tanpa Pemilik' }}
                            </option>
                        @endforeach
                    </select>
                </div>
    
                <div class="col-span-1 md:col-span-2">
                    <label class="block text-sm font-bold text-gray-700 mb-1">Nomor STNK *</label>
                    <input type="text" name="no_stnk" value="{{ old('no_stnk') }}" required placeholder="Contoh: 12345678" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-shadow bg-gray-50 focus:bg-white appearance-none">
                </div>
    
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-1">Tgl Jatuh Tempo Pajak (Tahunan) *</label>
                    <input type="date" name="tgl_jatuh_tempo_pajak" value="{{ old('tgl_jatuh_tempo_pajak') }}" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-shadow bg-gray-50 focus:bg-white appearance-none">
                </div>
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-1">Tgl Habis STNK (5 Tahunan) *</label>
                    <input type="date" name="tgl_habis_stnk" value="{{ old('tgl_habis_stnk') }}" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-shadow bg-gray-50 focus:bg-white appearance-none">
                </div>
    
                <div class="col-span-1 md:col-span-2">
                    <label class="block text-sm font-bold text-gray-700 mb-1">Status Aktif *</label>
                    <select name="status_aktif" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-shadow bg-gray-50 focus:bg-white appearance-none">
                        <option value="1" {{ old('status_aktif') == '1' ? 'selected' : '' }}>Aktif (STNK Terbaru)</option>
                        <option value="0" {{ (old('status_aktif') !== null && old('status_aktif') == '0') ? 'selected' : '' }}>Nonaktif (Hanya Riwayat/Arsip)</option>
                    </select>
                    <small class="text-xs text-gray-500 mt-1">* Memilih "Aktif" akan menonaktifkan catatan STNK sebelumnya untuk kendaraan ini (jika ada).</small>
                </div>

            </div>

            <div class="mt-8 flex gap-3">
                <button type="submit" class="px-6 py-2.5 bg-gray-900 hover:bg-gray-800 text-white font-bold rounded-lg shadow-sm transition-colors">Simpan Data</button>
                <a href="{{ route('stnk_records.index') }}" class="px-6 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 font-bold rounded-lg transition-colors">Batal</a>
            </div>

        </form>
    </div>
@endsection
