@extends('layouts.app')

@section('title', 'Data Klien')
@section('header_title', 'Manajemen Data Klien')

@section('content')
    <!-- Action Bar (Tombol Tambah & Search) -->
    <div class="flex flex-col md:flex-row justify-between items-center gap-4 mb-6">
        <!-- Kotak Pencarian -->
        <form action="{{ route('clients.index') }}" method="GET" class="flex w-full md:w-auto gap-2 order-2 md:order-1">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama, NIK, atau WA..." class="w-full md:w-72 px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-shadow">
            
            @if(request('search'))
                <a href="{{ route('clients.index') }}" class="px-4 py-2 bg-red-50 hover:bg-red-100 text-red-600 font-semibold rounded-lg transition-colors flex items-center"><i class="fa-solid fa-rotate-left"></i></a>
            @endif
        </form>

        <a href="{{ route('clients.create') }}" class="px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg shadow-sm transition-colors w-full md:w-auto text-center flex items-center justify-center gap-2 order-1 md:order-2">
            <i class="fa-solid fa-plus"></i> Tambah Klien Baru
        </a>
    </div>

    <!-- Tabel Data Klien -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-300 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50 border-b border-gray-300 text-sm text-gray-600 uppercase tracking-wider">
                        <th class="p-4 font-semibold">Nama Lengkap</th>
                        <th class="p-4 font-semibold">NIK</th>
                        <th class="p-4 font-semibold">No. WhatsApp</th>
                        <th class="p-4 font-semibold">Email</th>
                        <th class="p-4 font-semibold">Alamat</th>
                        <th class="p-4 font-semibold text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="text-sm divide-y divide-gray-100">
                    @forelse($clients as $client)
                    <tr class="hover:bg-gray-50/80 transition-colors">
                        <td class="p-4 font-semibold text-gray-800">{{ $client->nama_lengkap }}</td>
                        <td class="p-4 text-gray-600">{{ $client->nik ?? '-' }}</td>
                        <td class="p-4 text-gray-600">
                            <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $client->no_whatsapp) }}" target="_blank" class="text-green-600 hover:text-green-700 font-semibold hover:underline flex items-center gap-1.5">
                                <i class="fa-brands fa-whatsapp text-lg"></i> {{ $client->no_whatsapp }}
                            </a>
                        </td>
                        <td class="p-4 text-gray-500">{{ $client->email ?? '-' }}</td>
                        <td class="p-4 text-gray-500 max-w-[220px] truncate" title="{{ $client->alamat ?? '-' }}">{{ $client->alamat ?? '-' }}</td>
                        <td class="p-4">
                            <div class="flex justify-center gap-2">
                                <a href="{{ route('clients.edit', $client->id) }}" class="px-3 py-1.5 bg-blue-50 hover:bg-blue-100 text-blue-700 text-xs font-semibold rounded-lg transition-colors flex items-center gap-1.5">
                                    <i class="fa-solid fa-pen-to-square"></i> Edit
                                </a>
                                @if (Auth::user()->role === 'super_admin')
                                <form action="{{ route('clients.destroy', $client->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus klien ini beserta seluruh kendaraan dan transaksinya?');" class="inline">
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
                        <td colspan="6" class="p-10 text-center text-gray-500 text-base">Belum ada data klien yang tersimpan.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Paginations -->
        @if($clients->hasPages())
            <div class="px-5 py-4 border-t border-gray-300 flex flex-col sm:flex-row justify-between items-center gap-3">
                <div class="text-sm text-gray-500">
                    Menampilkan <span class="font-semibold text-gray-700">{{ $clients->firstItem() }}</span> - <span class="font-semibold text-gray-700">{{ $clients->lastItem() }}</span> dari <span class="font-semibold text-gray-700">{{ $clients->total() }}</span> data
                </div>
                <div class="flex items-center gap-1">
                    @if($clients->onFirstPage())
                        <span class="px-3 py-2 border border-gray-300 rounded-lg text-gray-300 bg-gray-50">
                            <i class="fa-solid fa-chevron-left"></i>
                        </span>
                    @else
                        <a href="{{ $clients->withQueryString()->previousPageUrl() }}" class="px-3 py-2 border border-gray-300 rounded-lg text-gray-600 bg-white hover:bg-blue-50 hover:text-blue-700 transition-colors">
                            <i class="fa-solid fa-chevron-left"></i>
                        </a>
                    @endif

                    @foreach($clients->getUrlRange(1, $clients->lastPage()) as $page => $url)
                        @if($page == $clients->currentPage())
                            <span class="px-3 py-2 rounded-lg bg-blue-600 text-white font-semibold">{{ $page }}</span>
                        @else
                            <a href="{{ $clients->withQueryString()->url($page) }}" class="px-3 py-2 border border-gray-300 rounded-lg text-gray-600 bg-white hover:bg-blue-50 hover:text-blue-700 transition-colors">{{ $page }}</a>
                        @endif
                    @endforeach

                    @if($clients->hasMorePages())
                        <a href="{{ $clients->withQueryString()->nextPageUrl() }}" class="px-3 py-2 border border-gray-300 rounded-lg text-gray-600 bg-white hover:bg-blue-50 hover:text-blue-700 transition-colors">
                            <i class="fa-solid fa-chevron-right"></i>
                        </a>
                    @else
                        <span class="px-3 py-2 border border-gray-300 rounded-lg text-gray-300 bg-gray-50">
                            <i class="fa-solid fa-chevron-right"></i>
                        </span>
                    @endif
                </div>
            </div>
        @endif
    </div>
@endsection