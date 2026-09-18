@extends('layouts.app')

@section('title', 'Data Kendaraan')
@section('header_title', 'Manajemen Data Kendaraan')

@section('content')

    <!-- Action Bar (Tombol Tambah & Search) -->
    <div class="flex flex-col md:flex-row justify-between items-center gap-4 mb-6">
        <!-- Kotak Pencarian -->
        <form action="{{ route('vehicles.index') }}" method="GET" class="flex w-full md:w-auto gap-2">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari Nopol, Merk, atau Pemilik..." class="w-full md:w-80 px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-shadow">
            
            @if(request('search'))
                <a href="{{ route('vehicles.index') }}" class="px-4 py-2 bg-red-50 hover:bg-red-100 text-red-600 font-semibold rounded-lg transition-colors flex items-center"><i class="fa-solid fa-rotate-left"></i></a>
            @endif
        </form>


        <a href="{{ route('vehicles.create') }}" class="px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg shadow-sm transition-colors w-full md:w-auto text-center flex items-center justify-center gap-2">
            <i class="fa-solid fa-plus"></i> Tambah Kendaraan Baru
        </a>
    </div>

    <!-- Tabel Data Kendaraan -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-300 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50 border-b border-gray-300 text-sm text-gray-600 uppercase tracking-wider">
                        <th class="p-4 font-semibold">No. Polisi</th>
                        <th class="p-4 font-semibold">Merk & Tipe</th>
                        <th class="p-4 font-semibold">Nama di STNK</th>
                        <th class="p-4 font-semibold">Klien (Pemilik)</th>
                        <th class="p-4 font-semibold text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="text-sm divide-y divide-gray-100">
                    @forelse($vehicles as $vehicle)
                    <tr class="hover:bg-gray-50/80 transition-colors">
                        <td class="p-4 font-semibold text-gray-800">{{ preg_replace('/([A-Z]+)(\d+)([A-Z]+)/', '$1 $2 $3', strtoupper($vehicle->nopol)) }}</td>
                        <td class="p-4 text-gray-700">
                            {{ $vehicle->merk }} <span class="text-gray-400">&middot;</span> {{ $vehicle->tipe ?? '-' }}
                        </td>
                        <td class="p-4 text-gray-600">{{ $vehicle->nama_pemilik }}</td>
                        <td class="p-4 text-gray-700 font-medium">{{ $vehicle->client->nama_lengkap ?? 'Tanpa Pemilik' }}</td>
                        <td class="p-4">
                            <div class="flex flex-wrap justify-center gap-2">
                                <a href="{{ route('vehicles.show', $vehicle->id) }}" class="px-3 py-1.5 bg-purple-50 hover:bg-purple-100 text-purple-700 text-xs font-semibold rounded-lg transition-colors flex items-center gap-1.5">
                                    <i class="fa-solid fa-eye"></i> Detail
                                </a>
                                <a href="{{ route('vehicles.edit', $vehicle->id) }}" class="px-3 py-1.5 bg-blue-50 hover:bg-blue-100 text-blue-700 text-xs font-semibold rounded-lg transition-colors flex items-center gap-1.5">
                                    <i class="fa-solid fa-pen-to-square"></i> Edit
                                </a>
                                @if(Auth::user()->role === 'super_admin')
                                <form action="{{ route('vehicles.destroy', $vehicle->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus data kendaraan ini?');" class="inline">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="px-3 py-1.5 bg-red-50 hover:bg-red-100 text-red-600 text-xs font-semibold rounded-lg transition-colors flex items-center gap-1.5">
                                        <i class="fa-solid fa-trash-can"></i> Hapus
                                    </button>
                                </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="p-10 text-center text-gray-500 text-base">Belum ada data kendaraan yang tersimpan.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Paginations -->
        @if($vehicles->hasPages())
            <div class="px-5 py-4 border-t border-gray-300 flex flex-col sm:flex-row justify-between items-center gap-3">
                <div class="text-sm text-gray-500">
                    Menampilkan <span class="font-semibold text-gray-700">{{ $vehicles->firstItem() }}</span> - <span class="font-semibold text-gray-700">{{ $vehicles->lastItem() }}</span> dari <span class="font-semibold text-gray-700">{{ $vehicles->total() }}</span> data
                </div>
                <div class="flex items-center gap-1">
                    @if($vehicles->onFirstPage())
                        <span class="px-3 py-2 border border-gray-200 rounded-lg text-gray-300 bg-gray-50">
                            <i class="fa-solid fa-chevron-left"></i>
                        </span>
                    @else
                        <a href="{{ $vehicles->withQueryString()->previousPageUrl() }}" class="px-3 py-2 border border-gray-200 rounded-lg text-gray-600 bg-white hover:bg-blue-50 hover:text-blue-700 transition-colors">
                            <i class="fa-solid fa-chevron-left"></i>
                        </a>
                    @endif

                    @foreach($vehicles->getUrlRange(1, $vehicles->lastPage()) as $page => $url)
                        @if($page == $vehicles->currentPage())
                            <span class="px-3 py-2 rounded-lg bg-blue-600 text-white font-semibold">{{ $page }}</span>
                        @else
                            <a href="{{ $vehicles->withQueryString()->url($page) }}" class="px-3 py-2 border border-gray-200 rounded-lg text-gray-600 bg-white hover:bg-blue-50 hover:text-blue-700 transition-colors">{{ $page }}</a>
                        @endif
                    @endforeach

                    @if($vehicles->hasMorePages())
                        <a href="{{ $vehicles->withQueryString()->nextPageUrl() }}" class="px-3 py-2 border border-gray-200 rounded-lg text-gray-600 bg-white hover:bg-blue-50 hover:text-blue-700 transition-colors">
                            <i class="fa-solid fa-chevron-right"></i>
                        </a>
                    @else
                        <span class="px-3 py-2 border border-gray-200 rounded-lg text-gray-300 bg-gray-50">
                            <i class="fa-solid fa-chevron-right"></i>
                        </span>
                    @endif
                </div>
            </div>
        @endif


    </div>
@endsection