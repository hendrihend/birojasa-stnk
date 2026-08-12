@extends('layouts.app')

@section('title', 'Dokumen Kendaraan')
@section('header_title', 'Arsip Dokumen: ' . $vehicle->nopol)

@section('content')
    <!-- Tombol Kembali -->
    <a href="{{ route('vehicles.index') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-white border border-gray-300 rounded-lg text-gray-700 font-bold hover:bg-gray-50 transition-colors mb-6 shadow-sm"><span>&larr;</span> Kembali ke Daftar Kendaraan</a>

    <!-- Menampilkan pesan sukses/error -->
    @if(session('success'))
        <div class="mb-6 px-4 py-3 bg-green-100 border-l-4 border-green-500 text-green-700 rounded shadow-sm flex items-center">
            <span class="mr-2">{{ session('success') }}</span>
        </div>
    @endif
    @if($errors->any())
        <div class="mb-6 px-4 py-3 bg-red-50 border-l-4 border-red-500 text-red-700 rounded shadow-sm">
            <ul class="list-disc list-inside text-sm font-medium">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">

        <div class="bg-gray-900 text-white rounded-xl shadow-sm p-6 border border-gray-800 h-fit">
            <h3 class="text-lg font-bold mb-4 text-blue-400 border-b border-gray-700 pb-2">Informasi Kendaraan</h3>
            <div class="space-y-3 text-sm">
                <div>
                    <p class="text-gray-400 text-xs">Nomor Polisi</p>
                    <p class="font-bold text-lg tracking-wider uppercase">{{ $vehicle->nopol }}</p>
                </div>
                <div>
                    <p class="text-gray-400 text-xs">Merk & Tipe</p>
                    <p class="font-medium">{{ $vehicle->merk }} {{ $vehicle->tipe }}</p>
                </div>
                <div>
                    <p class="text-gray-400 text-xs">Nama Pemilik (Klien)</p>
                    <p class="font-medium">{{ $vehicle->client->nama_lengkap ?? 'Tanpa Pemilik' }}</p>
                </div>
            </div>
        </div>
        
        <!-- BAGIAN KIRI: Form Upload -->
        <div class="lg:col-span-2 bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <h3 class="text-lg font-bold text-gray-800 mb-4 border-b border-gray-100 pb-2">Unggah Dokumen Baru</h3>
            
            <form action="{{ route('documents.store', $vehicle->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mb-5">
                    <div class="col-span-1 md:col-span-2">
                        <label class="block text-sm font-bold text-gray-700 mb-1">Jenis Dokumen</label>
                        <select name="jenis_dokumen" required class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none bg-gray-50 focus:bg-white">
                            <option value="">-- Pilih Jenis Dokumen --</option>
                            <option value="KTP">KTP (Pemilik)</option>
                            <option value="STNK">STNK (Asli/Fotokopi)</option>
                            <option value="BPKB">BPKB</option>
                            <option value="Faktur">Faktur Kendaraan</option>
                            <option value="Kwitansi">Kwitansi Pembelian</option>
                            <option value="Surat Jalan">Surat Jalan / Pengantar</option>
                        </select>
                    </div>
    
                    <div class="col-span-1 md:col-span-2">
                        <label class="block text-sm font-bold text-gray-700 mb-1">Pilih File (JPG/PNG/PDF)</label>
                        <input type="file" name="file_dokumen" accept=".jpg,.jpeg,.png,.pdf" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-shadow bg-gray-50 focus:bg-white appearance-none">
                        <small class="text-xs text-gray-500 mt-1">* Maksimal ukuran file 2MB</small>
                    </div>
                </div>

                <div class="mt-8 flex gap-3 col-span-1 md:col-span-2">
                    <button type="submit" class="w-full px-6 py-2.5 bg-gray-900 hover:bg-gray-800 text-white font-bold rounded-lg shadow-sm transition-colors">Upload File</button>
                </div>

            </form>
        </div>

        
    </div>
    <!-- BAGIAN KANAN: Tabel Daftar Dokumen -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="p-5 border-b border-gray-100 bg-gray-50/50">
            <h2 class="text-lg font-bold text-gray-800">Daftar Dokumen Tersimpan</h2>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50 border-b border-gray-200 text-sm text-gray-600 uppercase tracking-wider">
                        <th class="p-4 font-bold">Jenis Dokumen</th>
                        <th class="p-4 font-bold text-center">Preview</th>
                        <th class="p-4 font-bold">Tanggal Upload</th>
                        @if(Auth::user()->role === 'super_admin')
                        <th class="p-4 font-bold text-center">Aksi</th>
                        @endif
                    </tr>
                </thead>
                <tbody class="text-sm divide-y divide-gray-100">
                    @forelse($documents as $doc)
                    <tr class="hover:bg-gray-50/80 transition-colors">
                        <td class="p-4 font-bold text-gray-800">{{ $doc->jenis_dokumen }}</td>
                        <td class="p-4 text-center">
                            <!-- Link untuk melihat file. asset('storage/...') merujuk ke public/storage -->
                            <a href="{{ asset('storage/' . $doc->file_path) }}" target="_blank">Lihat File &nearr;</a>
                        </td>
                        <td class="p-4 text-gray-500">{{ $doc->created_at->setTimezone('Asia/Jakarta')->format('d M Y H:i:s') }}</td>
                        @if(Auth::user()->role === 'super_admin' ? '4' : '3' )
                        <td class="p-4 text-center">
                            <!-- Tombol Hapus -->
                            <form action="{{ route('documents.destroy', $doc->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus dokumen ini secara permanen?');" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-500 hover:text-red-700 font-bold px-3 py-1 hover:bg-red-50 rounded transition-colors">Hapus</button>
                            </form>
                        </td>
                        @endif
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" style="text-align: center; padding: 30px; color: #999;">Belum ada dokumen yang diunggah untuk kendaraan ini.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
