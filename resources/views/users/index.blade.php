@extends('layouts.app')

@section('title', 'Manajemen User')
@section('header_title', 'Kelola Pengguna Sistem')

@section('content')

    <!-- Action Bar (Tombol Tambah) -->
    <div class="flex flex-col md:flex-row justify-end items-center gap-4 mb-6">
        <a href="{{ route('users.create') }}" class="px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg shadow-sm transition-colors w-full md:w-auto text-center flex items-center justify-center gap-2">
            <i class="fa-solid fa-plus"></i> Tambah User Baru
        </a>
    </div>

    <!-- Tabel Data Users -->
     <div class="bg-white rounded-xl shadow-sm border border-gray-300 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50 border-b border-gray-300 text-sm text-gray-600 uppercase tracking-wider">
                        <th class="p-4 font-semibold">Nama</th>
                        <th class="p-4 font-semibold">Email</th>
                        <th class="p-4 font-semibold">Hak Akses (Role)</th>
                        <th class="p-4 font-semibold text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="text-sm divide-y divide-gray-100">
                    @foreach($users as $user)
                    <tr class="hover:bg-gray-50/80 transition-colors">
                        <td class="p-4 font-semibold text-gray-800">{{ $user->name }}</td>
                        <td class="p-4 text-gray-600">{{ $user->email ?? '-' }}</td>
                        <td class="p-4">
                            <span class="px-3 py-1 text-xs font-semibold rounded-full {{ $user->role === 'super_admin' ? 'bg-blue-50 text-blue-700' : 'bg-gray-100 text-gray-600' }}">
                                {{ strtoupper($user->role) }}
                            </span>
                        </td>
                        <td class="p-4">
                            <div class="flex justify-center">
                                @if (auth()->id() != $user->id)
                                <form action="{{ route('users.destroy', $user->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus user ini?');" class="inline">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="px-3 py-1.5 bg-red-50 hover:bg-red-100 text-red-600 text-xs font-semibold rounded-lg transition-colors flex items-center gap-1.5">
                                        <i class="fa-solid fa-trash-can"></i> Hapus
                                    </button>
                                </form>
                                @else
                                <span class="text-xs text-gray-400 bg-gray-50 px-3 py-1.5 rounded-lg font-medium">Akun Anda</span>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

@endsection