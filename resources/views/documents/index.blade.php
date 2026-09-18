@extends('layouts.app')

@section('title', 'Dokumen Kendaraan')
@section('header_title', 'Arsip Dokumen: ' . $vehicle->nopol)

@section('content')
    <!-- Tombol Kembali -->
    <a href="{{ route('vehicles.index') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-white border border-gray-300 rounded-lg text-gray-700 font-semibold hover:bg-gray-50 transition-colors mb-6 shadow-sm">
        <i class="fa-solid fa-arrow-left text-sm"></i> Kembali ke Daftar Kendaraan
    </a>

    @if($errors->any())
        <div class="mb-6 px-4 py-3 bg-red-50 border-l-4 border-red-500 text-red-700 rounded shadow-sm">
            <ul class="list-disc list-inside text-sm font-medium">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-4 gap-6 mb-8">

        <!-- Info Kendaraan -->
        <div class="lg:col-span-2 bg-white rounded-xl shadow-sm border border-gray-300 p-6 h-fit">
            <h3 class="text-lg font-semibold text-gray-800 mb-4 border-b border-gray-200 pb-3 flex items-center gap-2">
                <span class="w-8 h-8 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center text-sm">
                    <i class="fa-solid fa-car"></i>
                </span>
                Informasi Kendaraan
            </h3>
            <div class="grid grid-cols-2 gap-x-4 gap-y-4 text-sm">
                <div class="col-span-2">
                    <p class="text-gray-400 text-xs font-semibold uppercase tracking-wide">Nomor Polisi</p>
                    <p class="font-semibold text-lg text-gray-800 tracking-wider uppercase">{{ $vehicle->nopol }}</p>
                </div>
                <div class="col-span-2">
                    <p class="text-gray-400 text-xs font-semibold uppercase tracking-wide">Merk & Tipe</p>
                    <p class="font-medium text-gray-800">{{ $vehicle->merk }} {{ $vehicle->tipe }}</p>
                </div>
                <div>
                    <p class="text-gray-400 text-xs font-semibold uppercase tracking-wide">Nomor Rangka</p>
                    <p class="font-medium text-gray-800">{{ $vehicle->no_rangka }}</p>
                </div>
                <div>
                    <p class="text-gray-400 text-xs font-semibold uppercase tracking-wide">Nomor Mesin</p>
                    <p class="font-medium text-gray-800">{{ $vehicle->no_mesin }}</p>
                </div>
                <div>
                    <p class="text-gray-400 text-xs font-semibold uppercase tracking-wide">Nama Klien</p>
                    <p class="font-medium text-gray-800">{{ $vehicle->client->nama_lengkap ?? 'Tanpa Pemilik' }}</p>
                </div>
                <div>
                    <p class="text-gray-400 text-xs font-semibold uppercase tracking-wide">Nama di STNK</p>
                    <p class="font-medium text-gray-800">{{ $vehicle->nama_pemilik ?? 'Tanpa Pemilik' }}</p>
                </div>
                <div>
                    <p class="text-gray-400 text-xs font-semibold uppercase tracking-wide">Tahun Pembuatan</p>
                    <p class="font-medium text-gray-800">{{ $vehicle->tahun_pembuatan }}</p>
                </div>
                <div>
                    <p class="text-gray-400 text-xs font-semibold uppercase tracking-wide">Warna</p>
                    <p class="font-medium text-gray-800">{{ $vehicle->warna }}</p>
                </div>
            </div>
        </div>
        
        <!-- Form Upload -->
        <div class="lg:col-span-2 bg-white rounded-xl shadow-sm border border-gray-300 p-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4 border-b border-gray-200 pb-3">Unggah Dokumen Baru</h3>
            
            <form action="{{ route('documents.store', $vehicle->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mb-5">
                    <div class="col-span-1 md:col-span-2">
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Jenis Dokumen</label>
                        <select name="jenis_dokumen" required class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none bg-gray-50 focus:bg-white">
                            <option value="">-- Pilih Jenis Dokumen --</option>
                            <option value="KTP">KTP (Pemilik)</option>
                            <option value="STNK">STNK (Asli/Fotokopi)</option>
                            <option value="BPKB">BPKB</option>
                            <option value="Hasil Cek Fisik Kendaraan">Hasil Cek Fisik Kendaraan</option>
                            <option value="Kwitansi">Kwitansi Pembelian</option>
                        </select>
                    </div>
    
                    <div class="col-span-1 md:col-span-2">
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Pilih File (JPG/PNG/PDF)</label>
                        <input type="file" name="file_dokumen" accept=".jpg,.jpeg,.png,.pdf" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-shadow bg-gray-50 focus:bg-white appearance-none">
                        <small class="text-xs text-gray-500 mt-1">* Maksimal ukuran file 2MB</small>
                    </div>
                </div>

                <div class="mt-8 flex gap-3 col-span-1 md:col-span-2">
                    <button type="submit" class="w-full px-6 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg shadow-sm transition-colors flex items-center justify-center gap-2">
                        <i class="fa-solid fa-upload"></i> Upload File
                    </button>
                </div>

            </form>
        </div>

        
    </div>
    <!-- Tabel Daftar Dokumen -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-300 overflow-hidden">
        <div class="p-5 border-b border-gray-200 bg-gray-50">
            <h2 class="text-lg font-semibold text-gray-800">Daftar Dokumen Tersimpan</h2>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50 border-b border-gray-300 text-sm text-gray-600 uppercase tracking-wider">
                        <th class="p-4 font-semibold">Jenis Dokumen</th>
                        <th class="p-4 font-semibold text-center">Preview</th>
                        <th class="p-4 font-semibold">Tanggal Upload</th>
                        @if(Auth::user()->role === 'super_admin')
                        <th class="p-4 font-semibold text-center">Aksi</th>
                        @endif
                    </tr>
                </thead>
                <tbody class="text-sm divide-y divide-gray-300">
                    @forelse($documents as $doc)
                    <tr class="hover:bg-gray-50/80 transition-colors">
                        <td class="p-4 font-semibold text-gray-800">{{ $doc->jenis_dokumen }}</td>
                        <td class="p-4 text-center">
                            <!-- Link untuk melihat file. asset('storage/...') merujuk ke public/storage -->
                            <a href="{{ asset('storage/' . $doc->file_path) }}" target="_blank" class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-blue-50 hover:bg-blue-100 text-blue-700 text-xs font-semibold rounded-lg transition-colors">
                                <i class="fa-solid fa-arrow-up-right-from-square"></i> Lihat File
                            </a>
                        </td>
                        <td class="p-4 text-gray-500">{{ $doc->created_at->setTimezone('Asia/Jakarta')->format('d M Y H:i:s') }}</td>
                        @if(Auth::user()->role === 'super_admin')
                        <td class="p-4 text-center">
                            <!-- Tombol Hapus -->
                            <form action="{{ route('documents.destroy', $doc->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus dokumen ini secara permanen?');" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-red-50 hover:bg-red-100 text-red-600 text-xs font-semibold rounded-lg transition-colors">
                                    <i class="fa-solid fa-trash-can"></i> Hapus
                                </button>
                            </form>
                        </td>
                        @endif
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="p-10 text-center text-gray-500 text-base">Belum ada dokumen yang diunggah untuk kendaraan ini.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection