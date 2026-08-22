@extends('layouts.app')

@section('title', 'Manajemen User')
@section('header_title', 'Kelola Pengguna Sistem')

@section('content')

    <!-- Action Bar (Tombol Tambah & Search) -->
    <div class="flex flex-col md:flex-row justify-between items-center gap-4 mb-6">
        <a href="{{ route('users.create') }}" class="px-5 py-2.5 bg-gray-900 hover:bg-gray-800 text-white font-bold rounded-lg shadow-sm transition-colors w-full md:w-auto text-center flex items-center justify-center gap-2">
            <i class="fa-solid fa-plus"></i> Tambah User Baru
        </a>
    </div>

    <!-- Tabel Data Users -->
     <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50 border-b border-gray-200 text-sm text-gray-600 uppercase tracking-wider">
                        <th class="p-4 font-bold">Nama</th>
                        <th class="p-4 font-bold">Email</th>
                        <th class="p-4 font-bold">Hak Akses (Role)</th>
                        <th class="p-4 font-bold text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="text-sm divide-y divide-gray-100">
                    @foreach($users as $user)
                    <tr class="hover:bg-gray-50/80 transition-colors">
                        <td class="p-4 font-bold text-gray-800">{{ $user->name }}</td>
                        <td class="p-4 text-gray-600">{{ $user->email ?? '-' }}</td>
                        <td class="p-4">
                            <span class="px-2 py-1 text-xs font-bold rounded {{ $user->role === 'super_admin' ? 'bg-[#e8f4f8] text-[#2980b9]' : 'bg-[#f9f9f9] text-[#555]' }}">
                                {{ strtoupper($user->role) }}
                            </span>
                        </td>
                        <td class="p-4 flex justify-center gap-4">
                            @if (auth()->id() != $user->id)
                            <form action="{{ route('users.destroy', $user->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus user ini?');" class="inline">
                                @csrf @method('DELETE')
                                <button type="submit" class="text-red-500 hover:text-red-700 font-bold"><i class="fa-solid fa-trash-can"></i></button>
                            </form>
                            @else
                            <span class="text-gray-400">Akun Anda</span>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

   
@endsection