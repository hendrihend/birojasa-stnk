@extends('layouts.app')

@section('title', 'Data Klien')

@section('content')
    <div class="mb-6 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h2 class="text-xl font-semibold text-gray-900">Data Klien</h2>
            <p class="mt-1 text-sm text-gray-500">Kelola data klien biro jasa STNK</p>
        </div>
        <a href="{{ route('clients.create') }}" class="inline-flex items-center justify-center gap-2 rounded-lg bg-blue-700 px-5 py-2.5 text-sm font-semibold text-white transition hover:bg-blue-800">
            <i class="fa-solid fa-plus text-sm"></i>
            Tambah Klien
        </a>
    </div>

    <div class="overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm">
        <div class="border-b border-gray-200 p-5">
            <form action="{{ route('clients.index') }}" method="GET" class="flex w-full items-center gap-2">
                <div class="relative w-full max-w-md">
                    <i class="fa-solid fa-magnifying-glass absolute left-4 top-1/2 -translate-y-1/2 text-gray-400"></i>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama, NIK, atau WhatsApp..." class="h-12 w-full rounded-lg border border-gray-300 bg-white pl-11 pr-4 text-sm text-gray-700 outline-none transition focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100">
                </div>
                @if(request('search'))
                    <a href="{{ route('clients.index') }}" class="flex h-12 w-12 items-center justify-center rounded-lg bg-red-50 text-red-500 transition hover:bg-red-100" title="Reset pencarian">
                        <i class="fa-solid fa-rotate-left"></i>
                    </a>
                @endif
            </form>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full min-w-[900px] border-collapse text-left">
                <thead>
                    <tr class="border-b border-gray-200 bg-gray-50 text-sm text-gray-700">
                        <th class="px-7 py-4 font-semibold">No</th>
                        <th class="px-7 py-4 font-semibold">Nama Lengkap</th>
                        <th class="px-7 py-4 font-semibold">NIK</th>
                        <th class="px-7 py-4 font-semibold">No WhatsApp</th>
                        <th class="px-7 py-4 font-semibold">Alamat</th>
                        <th class="px-7 py-4 text-center font-semibold">Aksi</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-gray-100 text-sm text-gray-600">
                    @forelse($clients as $client)
                        <tr class="transition-colors hover:bg-gray-50">
                            <td class="px-7 py-4 text-gray-500">{{ $loop->iteration }}</td>
                            <td class="px-7 py-4 font-semibold text-gray-900">{{ $client->nama_lengkap }}</td>
                            <td class="px-7 py-4">{{ $client->nik ?? '-' }}</td>
                            <td class="px-7 py-4">
                                @if($client->no_whatsapp)
                                    <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $client->no_whatsapp) }}" target="_blank" class="inline-flex items-center gap-2 text-gray-600 transition hover:text-green-600">
                                        <i class="fa-brands fa-whatsapp text-base text-green-500"></i>
                                        {{ $client->no_whatsapp }}
                                    </a>
                                @else
                                    <span>-</span>
                                @endif
                            </td>
                            <td class="max-w-md truncate px-7 py-4 text-gray-600">{{ $client->alamat ?? '-' }}</td>
                            <td class="px-7 py-4">
                                <div class="flex items-center justify-center gap-2">
                                    <a href="{{ route('clients.edit', $client->id) }}" class="inline-flex items-center justify-center rounded-lg bg-indigo-50 px-3.5 py-2 text-sm font-medium text-blue-600 transition hover:bg-blue-100">
                                        Edit
                                    </a>
                                    @if(Auth::user()->role === 'super_admin')
                                        <form action="{{ route('clients.destroy', $client->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus klien ini beserta seluruh kendaraan dan transaksinya?');" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="inline-flex items-center justify-center rounded-lg bg-red-50 px-3.5 py-2 text-sm font-medium text-red-500 transition hover:bg-red-100">
                                                Hapus
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-7 py-12 text-center text-sm text-gray-500">
                                Belum ada data klien yang tersimpan.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection