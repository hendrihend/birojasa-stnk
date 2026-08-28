@extends('layouts.app')

@section('title', 'Tambah Kendaraan')
@section('header_title', 'Data Kendaraan')

@section('content')
    <div class="max-w-4xl mx-auto">
        <div class="mb-5">
            <h2 class="text-xl font-bold text-gray-900">Tambah Data Kendaraan Baru</h2>
            <p class="text-sm text-gray-500 mt-1">Lengkapi data kendaraan, STNK, dan dokumen kendaraan.</p>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            @if($errors->any())
                <div class="m-6 px-4 py-3 bg-red-50 border-l-4 border-red-500 text-red-700 rounded-lg shadow-sm">
                    <ul class="list-disc list-inside text-sm font-medium">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('vehicles.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <!-- DATA KENDARAAN -->
                <div class="p-7">
                    <h3 class="text-base font-bold text-gray-900 mb-5 border-b border-gray-100 pb-3">
                        <i class="fa-solid fa-car text-blue-900 mr-2"></i>Informasi Utama Kendaraan
                    </h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <div class="col-span-1 md:col-span-2">
                            <label class="block text-sm font-semibold text-gray-700 mb-1.5">Pilih Pemilik / Klien <span class="text-red-500">*</span></label>
                            <select name="client_id" required class="w-full px-4 py-2.5 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-900 focus:border-blue-900 outline-none transition-shadow bg-gray-50 focus:bg-white appearance-none">
                                <option value="">-- Pilih Klien --</option>
                                @foreach($clients as $client)
                                    <option value="{{ $client->id }}" {{ old('client_id') == $client->id ? 'selected' : '' }}>{{ $client->nama_lengkap }} ({{ $client->no_whatsapp }})</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1.5">Nomor Polisi <span class="text-red-500">*</span></label>
                            <input type="text" name="nopol" value="{{ old('nopol') }}" required placeholder="Contoh: B 1234 ABC" class="w-full px-4 py-2.5 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-900 focus:border-blue-900 outline-none transition-shadow bg-gray-50 focus:bg-white uppercase">
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1.5">Nomor Rangka <span class="text-red-500">*</span></label>
                            <input type="text" name="no_rangka" value="{{ old('no_rangka') }}" required placeholder="Contoh: MH30800" class="w-full px-4 py-2.5 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-900 focus:border-blue-900 outline-none transition-shadow bg-gray-50 focus:bg-white uppercase">
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1.5">Nomor Mesin</label>
                            <input type="text" name="no_mesin" value="{{ old('no_mesin') }}" placeholder="Contoh: H3J88Y838" class="w-full px-4 py-2.5 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-900 focus:border-blue-900 outline-none transition-shadow bg-gray-50 focus:bg-white uppercase">
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1.5">Merk Kendaraan</label>
                            <input type="text" name="merk" value="{{ old('merk') }}" placeholder="Contoh: Honda, Toyota" class="w-full px-4 py-2.5 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-900 focus:border-blue-900 outline-none transition-shadow bg-gray-50 focus:bg-white">
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1.5">Tipe Kendaraan</label>
                            <input type="text" name="tipe" value="{{ old('tipe') }}" placeholder="Contoh: Vario 150, Avanza" class="w-full px-4 py-2.5 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-900 focus:border-blue-900 outline-none transition-shadow bg-gray-50 focus:bg-white">
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1.5">Tahun Pembuatan</label>
                            <input type="number" name="tahun_pembuatan" value="{{ old('tahun_pembuatan') }}" placeholder="Contoh: 2020" min="1950" max="{{ date('Y') }}" class="w-full px-4 py-2.5 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-900 focus:border-blue-900 outline-none transition-shadow bg-gray-50 focus:bg-white">
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1.5">Warna Kendaraan</label>
                            <input type="text" name="warna" value="{{ old('warna') }}" placeholder="Contoh: Biru" class="w-full px-4 py-2.5 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-900 focus:border-blue-900 outline-none transition-shadow bg-gray-50 focus:bg-white">
                        </div>

                        <div class="col-span-1 md:col-span-2">
                            <label class="block text-sm font-semibold text-gray-700 mb-1.5">Nama Tercetak di STNK</label>
                            <input type="text" name="nama_pemilik" value="{{ old('nama_pemilik') }}" placeholder="Kosongkan jika namanya sama dengan nama Klien" class="w-full px-4 py-2.5 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-900 focus:border-blue-900 outline-none transition-shadow bg-gray-50 focus:bg-white">
                            <p class="text-xs text-gray-500 mt-1.5">Sistem otomatis menggunakan nama Klien jika dikosongkan.</p>
                        </div>
                    </div>
                </div>

                <!-- DATA STNK -->
                <div class="p-7 bg-gray-50 border-t border-gray-200">
                    <h3 class="text-base font-bold text-gray-900 mb-5 border-b border-gray-200 pb-3">
                        <i class="fa-regular fa-file-lines text-blue-900 mr-2"></i>Data STNK & Pajak
                        <span class="text-xs font-normal text-gray-500">(Opsional, bisa diisi nanti)</span>
                    </h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1.5">Nomor STNK</label>
                            <input type="text" name="no_stnk" value="{{ old('no_stnk') }}" placeholder="Kosongkan jika belum ada" class="w-full px-4 py-2.5 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-900 focus:border-blue-900 outline-none transition-shadow bg-white">
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1.5">Jatuh Tempo Pajak (Tahunan)</label>
                            <input type="date" name="tgl_jatuh_tempo_pajak" value="{{ old('tgl_jatuh_tempo_pajak') }}" class="w-full px-4 py-2.5 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-900 focus:border-blue-900 outline-none transition-shadow bg-white text-gray-700">
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1.5">Habis Masa STNK (5 Tahunan)</label>
                            <input type="date" name="tgl_habis_stnk" value="{{ old('tgl_habis_stnk') }}" class="w-full px-4 py-2.5 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-900 focus:border-blue-900 outline-none transition-shadow bg-white text-gray-700">
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1.5">Status Aktif <span class="text-red-500">*</span></label>
                            <select name="status_aktif" required class="w-full px-4 py-2.5 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-900 focus:border-blue-900 outline-none transition-shadow bg-white appearance-none">
                                <option value="1" {{ old('status_aktif') == '1' ? 'selected' : '' }}>Aktif (STNK Terbaru)</option>
                                <option value="0" {{ old('status_aktif') !== null && old('status_aktif') == '0' ? 'selected' : '' }}>Nonaktif (Hanya Riwayat/Arsip)</option>
                            </select>
                            <p class="text-xs text-gray-500 mt-1.5">Memilih "Aktif" akan menonaktifkan catatan STNK sebelumnya.</p>
                        </div>
                    </div>
                </div>

                <!-- UPLOAD DOKUMEN -->
                <div class="p-7 border-t border-gray-200">
                    <h3 class="text-base font-bold text-gray-900 mb-5 border-b border-gray-100 pb-3">
                        <i class="fa-solid fa-file-arrow-up text-blue-900 mr-2"></i>Unggah Dokumen Awal
                        <span class="text-xs font-normal text-gray-500">(Opsional, JPG/PNG/PDF max 2MB)</span>
                    </h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1.5">Jenis Dokumen</label>
                            <select name="jenis_dokumen" class="w-full px-4 py-2.5 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-900 focus:border-blue-900 outline-none transition-shadow bg-white appearance-none">
                                <option value="">-- Pilih Jika Ingin Mengunggah --</option>
                                <option value="KTP" {{ old('jenis_dokumen') == 'KTP' ? 'selected' : '' }}>KTP Pemilik</option>
                                <option value="STNK" {{ old('jenis_dokumen') == 'STNK' ? 'selected' : '' }}>Foto STNK Asli</option>
                                <option value="BPKB" {{ old('jenis_dokumen') == 'BPKB' ? 'selected' : '' }}>Foto BPKB</option>
                                <option value="Faktur" {{ old('jenis_dokumen') == 'Faktur' ? 'selected' : '' }}>Faktur Kendaraan</option>
                                <option value="Kwitansi" {{ old('jenis_dokumen') == 'Kwitansi' ? 'selected' : '' }}>Kwitansi Pembelian</option>
                                <option value="Surat Jalan" {{ old('jenis_dokumen') == 'Surat Jalan' ? 'selected' : '' }}>Surat Jalan / Pengantar</option>
                            </select>
                            <p class="text-xs text-gray-500 mt-1.5">Pilih jenis dokumen jika melampirkan file.</p>
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1.5">Pilih File</label>
                            <input type="file" name="file_dokumen" accept=".jpg,.jpeg,.png,.pdf" class="block w-full text-sm text-gray-500 border border-gray-300 rounded-lg cursor-pointer bg-white focus:outline-none file:mr-4 file:py-2.5 file:px-4 file:rounded-l-lg file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-900 hover:file:bg-blue-100 transition-colors">
                        </div>
                    </div>

                    <div class="mt-5 flex items-start gap-2 bg-blue-50 p-3 rounded-lg border border-blue-100">
                        <i class="fa-solid fa-circle-info text-blue-900 mt-0.5"></i>
                        <p class="text-xs text-blue-900 leading-relaxed">Anda bisa mengunggah 1 dokumen awal di sini, misalnya foto STNK. Dokumen tambahan dapat diunggah nanti melalui menu <strong>Data Kendaraan → Detail → Dokumen</strong>.</p>
                    </div>
                </div>

                <!-- BUTTON -->
                <div class="px-7 py-5 border-t border-gray-200 flex gap-3">
                    <button type="submit" class="px-6 py-2.5 bg-blue-900 hover:bg-blue-800 text-white text-sm font-semibold rounded-lg shadow-sm transition-colors flex items-center gap-2">
                        <i class="fa-solid fa-floppy-disk"></i> Simpan Data
                    </button>
                    <a href="{{ route('vehicles.index') }}" class="px-6 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-semibold rounded-lg transition-colors">Batal</a>
                </div>
            </form>
        </div>
    </div>
@endsection