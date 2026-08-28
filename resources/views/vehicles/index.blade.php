@extends('layouts.app')

@section('title', 'Data Kendaraan')
@section('header_title', 'Daftar Kendaraan')

@section('content')
<div class="mb-6 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
    <div>
        <h2 class="text-xl font-semibold text-gray-900">Data Kendaraan</h2>
        <p class="text-sm text-gray-500 mt-1">Kelola data kendaraan dan dokumen STNK klien.</p>
    </div>
    <a href="{{ route('vehicles.create') }}" class="inline-flex items-center justify-center gap-2 px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold rounded-lg transition-colors">
        <i class="fa-solid fa-plus"></i> Tambah Kendaraan
    </a>
</div>

<div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
    <div class="p-5 border-b border-gray-200">
        <form action="{{ route('vehicles.index') }}" method="GET" class="flex items-center gap-2">
            <div class="relative w-full md:w-96">
                <i class="fa-solid fa-magnifying-glass absolute left-4 top-1/2 -translate-y-1/2 text-gray-400"></i>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari Nopol, Merk, atau Pemilik..." class="w-full pl-11 pr-4 py-2.5 border border-gray-300 rounded-lg text-sm text-gray-700 placeholder-gray-400 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition">
            </div>
            @if(request('search'))
                <a href="{{ route('vehicles.index') }}" class="px-3.5 py-2.5 bg-red-50 hover:bg-red-100 text-red-600 rounded-lg text-sm transition-colors" title="Reset pencarian">
                    <i class="fa-solid fa-rotate-left"></i>
                </a>
            @endif
        </form>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-left">
            <thead>
                <tr class="bg-gray-50 border-b border-gray-200">
                    <th class="px-5 py-4 text-sm font-semibold text-gray-700">No. Polisi</th>
                    <th class="px-5 py-4 text-sm font-semibold text-gray-700">Merk & Tipe</th>
                    <th class="px-5 py-4 text-sm font-semibold text-gray-700">Nama di STNK</th>
                    <th class="px-5 py-4 text-sm font-semibold text-gray-700">Klien</th>
                    <th class="px-5 py-4 text-sm font-semibold text-gray-700 text-center">Dokumen</th>
                    <th class="px-5 py-4 text-sm font-semibold text-gray-700 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($vehicles as $vehicle)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-5 py-4">
                            <span class="inline-block px-3 py-1.5 bg-gray-100 text-gray-800 text-sm font-semibold rounded-md uppercase">
                                {{ $vehicle->nopol }}
                            </span>
                        </td>
                        <td class="px-5 py-4 text-sm text-gray-700">
                            <div class="font-medium text-gray-800">{{ $vehicle->merk }}</div>
                            <div class="text-xs text-gray-500 mt-0.5">{{ $vehicle->tipe ?? '-' }}</div>
                        </td>
                        <td class="px-5 py-4 text-sm text-gray-600">{{ $vehicle->nama_pemilik ?? '-' }}</td>
                        <td class="px-5 py-4">
                            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-blue-50 text-blue-700 border border-blue-100 rounded-full text-xs font-medium whitespace-nowrap">
                                <i class="fa-solid fa-user-tag"></i>
                                {{ $vehicle->client->nama_lengkap ?? 'Tanpa Pemilik' }}
                            </span>
                        </td>
                        <td class="px-5 py-4 text-center">
                            <div class="flex justify-center gap-2">
                                <a href="{{ route('documents.index', $vehicle->id) }}" class="w-8 h-8 inline-flex items-center justify-center bg-green-50 hover:bg-green-100 text-green-600 rounded-lg transition-colors" title="Dokumen">
                                    <i class="fa-solid fa-folder-open"></i>
                                </a>
                                <a href="{{ route('vehicles.show', $vehicle->id) }}" class="w-8 h-8 inline-flex items-center justify-center bg-purple-50 hover:bg-purple-100 text-purple-600 rounded-lg transition-colors" title="QR Code">
                                    <i class="fa-solid fa-qrcode"></i>
                                </a>
                            </div>
                        </td>
                        <td class="px-5 py-4 text-center">
                            <div class="flex justify-center gap-2">
                                <a href="{{ route('vehicles.edit', $vehicle->id) }}" class="w-8 h-8 inline-flex items-center justify-center bg-blue-50 hover:bg-blue-100 text-blue-600 rounded-lg transition-colors" title="Edit">
                                    <i class="fa-solid fa-pen-to-square"></i>
                                </a>
                                @if(Auth::user()->role === 'super_admin')
                                    <form action="{{ route('vehicles.destroy', $vehicle->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus data kendaraan ini?');" class="inline">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="w-8 h-8 inline-flex items-center justify-center bg-red-50 hover:bg-red-100 text-red-600 rounded-lg transition-colors" title="Hapus">
                                            <i class="fa-solid fa-trash-can"></i>
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-5 py-12 text-center text-sm text-gray-500">Belum ada data kendaraan yang tersimpan.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($vehicles->hasPages())
    <div class="px-5 py-4 border-t border-gray-200 flex flex-col sm:flex-row justify-between items-center gap-3">
        <div class="text-sm text-gray-500">
            Menampilkan <span class="font-semibold text-gray-700">{{ $vehicles->firstItem() }}</span> - <span class="font-semibold text-gray-700">{{ $vehicles->lastItem() }}</span> dari <span class="font-semibold text-gray-700">{{ $vehicles->total() }}</span> data
        </div>
        <div class="flex items-center gap-1">
            @if($vehicles->onFirstPage())
                <span class="px-3 py-2 border border-gray-200 rounded-lg text-gray-300 bg-gray-50">
                    <i class="fa-solid fa-chevron-left"></i>
                </span>
            @else
                <a href="{{ $vehicles->withQueryString()->previousPageUrl() }}" class="px-3 py-2 border border-gray-200 rounded-lg text-gray-600 bg-white hover:bg-blue-80 hover:text-blue-700 transition-colors">
                    <i class="fa-solid fa-chevron-left"></i>
                </a>
            @endif

            @foreach($vehicles->getUrlRange(1, $vehicles->lastPage()) as $page => $url)
                @if($page == $vehicles->currentPage())
                    <span class="px-3 py-2 rounded-lg bg-blue-900 text-white font-semibold">{{ $page }}</span>
                @else
                    <a href="{{ $vehicles->withQueryString()->url($page) }}" class="px-3 py-2 border border-gray-200 rounded-lg text-gray-600 bg-white hover:bg-blue-80 hover:text-blue-700 transition-colors">{{ $page }}</a>
                @endif
            @endforeach

            @if($vehicles->hasMorePages())
                <a href="{{ $vehicles->withQueryString()->nextPageUrl() }}" class="px-3 py-2 border border-gray-200 rounded-lg text-gray-600 bg-white hover:bg-blue-80 hover:text-blue-700 transition-colors">
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