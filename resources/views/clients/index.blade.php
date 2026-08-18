@extends('layouts.app')

@section('title', 'Data Klien')
@section('header_title', 'Manajemen Data Klien')

@section('content')
    <!-- Pesan Sukses -->
    @if(session('success'))
        <div class="mb-5 px-4 py-3 bg-green-100 border-l-4 border-green-500 text-green-700 rounded shadow-sm flex items-center gap-2">
            <i class="fa-solid fa-circle-check"></i> {{ session('success') }}
        </div>
    @endif

    <!-- Action Bar (Tombol Tambah & Search) -->
    <div class="flex flex-col md:flex-row justify-between items-center gap-4 mb-6">
        <a href="{{ route('clients.create') }}" class="px-5 py-2.5 bg-gray-900 hover:bg-gray-800 text-white font-bold rounded-lg shadow-sm transition-colors w-full md:w-auto text-center flex items-center justify-center gap-2">
            <i class="fa-solid fa-plus"></i> Tambah Klien Baru
        </a>

        <!-- Kotak Pencarian -->
        <form action="{{ route('clients.index') }}" method="GET" class="flex w-full md:w-auto gap-2">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama, NIK, atau WA..." class="w-full md:w-72 px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-shadow">
            
            @if(request('search'))
                <a href="{{ route('clients.index') }}" class="px-4 py-2 bg-red-100 hover:bg-red-200 text-red-600 font-bold rounded-lg transition-colors flex items-center"><i class="fa-solid fa-rotate-left"></i></a>
            @endif
        </form>
    </div>

    <!-- Tabel Data Klien -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50 border-b border-gray-200 text-sm text-gray-600 uppercase tracking-wider">
                        <th class="p-4 font-bold">Nama Lengkap</th>
                        <th class="p-4 font-bold">NIK</th>
                        <th class="p-4 font-bold">No. WhatsApp</th>
                        <th class="p-4 font-bold">Alamat</th>
                        <th class="p-4 font-bold text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="text-sm divide-y divide-gray-100">
                    @forelse($clients as $client)
                    <tr class="hover:bg-gray-50/80 transition-colors">
                        <td class="p-4 font-bold text-gray-800">{{ $client->nama_lengkap }}</td>
                        <td class="p-4 text-gray-600">{{ $client->nik ?? '-' }}</td>
                        <td class="p-4 text-gray-600">
                            <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $client->no_whatsapp) }}" target="_blank" class="text-green-600 hover:text-green-700 font-bold hover:underline flex items-center gap-1.5">
                                <i class="fa-brands fa-whatsapp text-lg"></i> {{ $client->no_whatsapp }}
                            </a>
                        </td>
                        <td class="p-4 text-gray-500 truncate max-w-xs">{{ $client->alamat ?? '-' }}</td>
                        <td class="p-4 flex justify-center gap-4">
                            <a href="{{ route('clients.edit', $client->id) }}" class="text-blue-500 hover:text-blue-700 font-bold"><i class="fa-solid fa-pen-to-square"></i></a>
                            @if (Auth::user()->role === 'super_admin')
                            <form action="{{ route('clients.destroy', $client->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus klien ini beserta seluruh kendaraan dan transaksinya?');" class="inline">
                                @csrf @method('DELETE')
                                <button type="submit" class="text-red-500 hover:text-red-700 font-bold"><i class="fa-solid fa-trash-can"></i></button>
                            </form>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="p-10 text-center text-gray-500 text-base">Belum ada data klien yang tersimpan.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
