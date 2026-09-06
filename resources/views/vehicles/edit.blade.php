@extends('layouts.app')
@section('title', 'Edit Kendaraan')
@section('header_title', 'Edit Data Kendaraan')

@section('content')
    <div class="max-w-4xl mx-auto bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        
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
            <!-- Data Kendaraan -->
            <div class="p-8">
                <h3 class="text-lg font-bold text-gray-800 mb-5 border-b border-gray-100 pb-2">
                    <i class="fa-solid fa-car text-blue-500 mr-2"></i> Data Kendaraan
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="col-span-1 md:col-span-2">
                        <label class="block text-sm font-bold text-gray-700 mb-1">Nama Klien</label>
                        <div class="w-full px-4 py-2 border border-gray-200 rounded-lg bg-gray-100 text-gray-600 flex items-center gap-3">
                            <span>{{ $vehicle->client->nama_lengkap ?? 'Tanpa Nama' }} &mdash; NIK: {{ $vehicle->client->nik}}</span>
                        </div>
                        <!-- Input hidden untuk mengirim ID Client agar tidak error saat validasi -->
                        <input type="hidden" name="client_id" value="{{ $vehicle->client_id }}">
                    </div>
        
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-1">Nomor Polisi (Nopol) <span class="text-red-500">*</span></label>
                        <input type="text" name="nopol" value="{{ old('nopol', $vehicle->nopol) }}" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-shadow bg-gray-50 focus:bg-white uppercase">
                    </div>
        
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-1">No Rangka <span class="text-red-500">*</span></label>
                        <input type="text" name="no_rangka" value="{{ old('no_rangka', $vehicle->no_rangka) }}" required required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-shadow bg-gray-50 focus:bg-white uppercase">
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-1">No Mesin <span class="text-red-500">*</span></label>
                        <input type="text" name="no_mesin" value="{{ old('no_mesin', $vehicle->no_mesin) }}" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-shadow bg-gray-50 focus:bg-white uppercase">
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-1">Merk <span class="text-red-500">*</span></label>
                        <input type="text" name="merk" value="{{ old('merk', $vehicle->merk) }}" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-shadow bg-gray-50 focus:bg-white uppercase">
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-1">Tipe <span class="text-red-500">*</span></label>
                        <input type="text" name="tipe" value="{{ old('tipe', $vehicle->tipe) }}" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-shadow bg-gray-50 focus:bg-white uppercase">
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-1">Tahun Pembuatan <span class="text-red-500">*</span></label>
                        <input type="number" name="tahun_pembuatan" value="{{ old('tahun_pembuatan', $vehicle->tahun_pembuatan) }}" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-shadow bg-gray-50 focus:bg-white">
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-1">Warna <span class="text-red-500">*</span></label>
                        <input type="text" name="warna" value="{{ old('warna', $vehicle->warna) }}" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-shadow bg-gray-50 focus:bg-white uppercase">
                    </div>
        
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-1">Nama Tercetak di STNK</label>
                        <input type="text" name="nama_pemilik" value="{{ old('nama_pemilik', $vehicle->nama_pemilik) }}" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-shadow bg-gray-50 focus:bg-white uppercase">
                    </div>
                </div>
            </div>

            <!-- Data STNK dan Pajak -->
             <div class="p-8 bg-gray-50 border-t border-gray-100">
                <div class="flex justify-between items-center mb-5 border-b border-gray-200 pb-2">
                    <h3 class="text-lg font-bold text-gray-800">
                        <i class="fa-regular fa-file-lines text-green-500 mr-2"></i> Data STNK & Pajak</span>
                    </h3>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-1">Nomor STNK <span class="text-red-500">*</span></label>
                        <input type="text" name="no_stnk" required placeholder="Contoh:11332208" value="{{ old('no_stnk', $vehicle->stnk->no_stnk ?? '') }}" 
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 outline-none transition-shadow bg-gray-50 focus:bg-white">
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-1">Jatuh Tempo Pajak (Tahunan) <span class="text-red-500">*</span></label>
                        <input type="date" name="tgl_jatuh_tempo_pajak" required value="{{ old('tgl_jatuh_tempo_pajak', optional($vehicle->stnk)->tgl_jatuh_tempo_pajak ? \Carbon\Carbon::parse($vehicle->stnk->tgl_jatuh_tempo_pajak)->format('Y-m-d') : '') }}" 
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 outline-none transition-shadow bg-gray-50 focus:bg-white">
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-1">Habis Masa STNK (5 Tahunan) <span class="text-red-500">*</span></label>
                        <input type="date" name="tgl_habis_stnk" required value="{{ old('tgl_habis_stnk', optional($vehicle->stnk)->tgl_habis_stnk ? \Carbon\Carbon::parse($vehicle->stnk->tgl_habis_stnk)->format('Y-m-d') : '') }}" 
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 outline-none transition-shadow bg-gray-50 focus:bg-white">
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-1">Status Aktif  <span class="text-red-500">*</span></label>
                        <select name="status_aktif" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 outline-none transition-shadow bg-gray-50 focus:bg-white appearance-none">
                            <option value="1" {{ old('status_aktif') == '1' ? 'selected' : '' }}>Aktif (STNK Terbaru)</option>
                            <option value="0" {{ (old('status_aktif') !== null && old('status_aktif') == '0') ? 'selected' : '' }}>Nonaktif (Hanya Riwayat/Arsip)</option>
                        </select>
                        <small class="text-xs text-gray-500 mt-1">* Memilih "Aktif" akan menonaktifkan catatan STNK sebelumnya untuk kendaraan ini (jika ada).</small>
                    </div>
                </div>
            </div>

            <div class="p-8 pt-1 flex gap-3">
                <button type="submit" class="px-6 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-lg shadow-sm transition-colors flex items-center gap-2">
                    <i class="fa-solid fa-floppy-disk"></i> Simpan Perubahan
                </button>
                <a href="{{ route('vehicles.index') }}" class="px-6 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 font-bold rounded-lg transition-colors">Batal</a>
            </div>

        </form>
    </div>


@endsection