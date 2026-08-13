@extends('layouts.app')

@section('title', 'Data Kendaraan')
@section('header_title', 'Manajemen Data Kendaraan')

@section('content')
    <!-- Pesan Sukses -->
    @if(session('success'))
        <div class="mb-5 px-4 py-3 bg-green-100 border-l-4 border-green-500 text-green-700 rounded shadow-sm flex items-center gap-2">
            <i class="fa-solid fa-circle-check"></i> {{ session('success') }}
        </div>
    @endif

    <!-- Action Bar (Tombol Tambah & Search) -->
    <div class="flex flex-col md:flex-row justify-between items-center gap-4 mb-6">
        @if(Auth::user()->role === 'super_admin')
            <a href="{{ route('vehicles.create') }}" class="px-5 py-2.5 bg-gray-900 hover:bg-gray-800 text-white font-bold rounded-lg shadow-sm transition-colors w-full md:w-auto text-center flex items-center justify-center gap-2">
                <i class="fa-solid fa-plus"></i> Tambah Kendaraan Baru
            </a>
        @else
            <!-- Spacer kosong agar form pencarian tetap di kanan untuk admin biasa -->
            <div></div> 
        @endif

        <!-- Kotak Pencarian -->
        <form action="{{ route('vehicles.index') }}" method="GET" class="flex w-full md:w-auto gap-2">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari Nopol, Merk, atau Pemilik..." class="w-full md:w-80 px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-shadow">
            
            @if(request('search'))
                <a href="{{ route('vehicles.index') }}" class="px-4 py-2 bg-red-100 hover:bg-red-200 text-red-600 font-bold rounded-lg transition-colors flex items-center"><i class="fa-solid fa-rotate-left"></i></a>
            @endif
        </form>
    </div>

    <!-- Tabel Data Kendaraan -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50 border-b border-gray-200 text-sm text-gray-600 uppercase tracking-wider">
                        <th class="p-4 font-bold">No. Polisi</th>
                        <th class="p-4 font-bold">Merk & Tipe</th>
                        <th class="p-4 font-bold">Nama di STNK</th>
                        <th class="p-4 font-bold">Klien (Pemilik)</th>
                        <th class="p-4 font-bold text-center">Preview</th>
                        @if(Auth::user()->role === 'super_admin')
                        <th class="p-4 font-bold text-center">Aksi</th>
                        @endif
                    </tr>
                </thead>
                <tbody class="text-sm divide-y divide-gray-100">
                    @forelse($vehicles as $vehicle)
                    <tr class="hover:bg-gray-50/80 transition-colors">
                        <td class="p-4 font-black text-gray-800 tracking-wider uppercase">{{ $vehicle->nopol }}</td>
                        <td class="p-4 text-gray-700">
                            {{ $vehicle->merk }} 
                            <span class="text-xs font-bold bg-gray-200 text-gray-600 px-2 py-1 rounded ml-1">{{ $vehicle->tipe ?? '-' }}</span>
                        </td>
                        <td class="p-4 text-gray-600">{{ $vehicle->nama_pemilik }}</td>
                        <td class="p-4">
                            <span class="font-bold text-blue-700 bg-blue-50 border border-blue-100 px-3 py-1.5 rounded-full whitespace-nowrap flex items-center gap-1.5 w-max">
                                <i class="fa-solid fa-user-tag"></i> {{ $vehicle->client->nama_lengkap ?? 'Tanpa Pemilik' }}
                            </span>
                        </td>
                        <td class="p-4 text-center">
                            <a href="{{ route('documents.index', $vehicle->id) }}" class="text-green-600 hover:text-green-800 font-bold px-2 py-1 hover:bg-green-50 rounded transition-colors items-center gap-1"><i class="fa-solid fa-folder-open"></i></a>
                            <a href="{{ route('vehicles.show', $vehicle->id) }}" class="text-purple-600 hover:text-purple-800 font-bold px-2 py-1 hover:bg-purple-50 rounded transition-colors items-center gap-1"><i class="fa-solid fa-qrcode"></i></a>
                        </td>
                        @if(Auth::user()->role === 'super_admin')
                        <td class="p-4 text-center">
                            <a href="{{ route('vehicles.edit', $vehicle->id) }}" class="text-blue-500 hover:text-blue-700 font-bold px-2 py-1 hover:bg-blue-50 rounded transition-colors"><i class="fa-solid fa-pen-to-square"></i></a>
                            <form action="{{ route('vehicles.destroy', $vehicle->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus data kendaraan ini?');" class="inline">
                                @csrf @method('DELETE')
                                <button type="submit" class="text-red-500 hover:text-red-700 font-bold px-2 py-1 hover:bg-red-50 rounded transition-colors"><i class="fa-solid fa-trash-can"></i></button>
                            </form>
                        </td>
                        @endif
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="p-10 text-center text-gray-500 text-base">Belum ada data kendaraan yang tersimpan.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
