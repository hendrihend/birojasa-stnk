@extends('layouts.app')

@section('title', 'Manajemen User')
@section('header_title', 'Kelola Pengguna Sistem')

@section('content')

    <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm">

        {{-- Alert Success --}}
        @if (session('success'))
            <div class="mb-4 rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-700">
                {{ session('success') }}
            </div>
        @endif

        {{-- Alert Error --}}
        @if ($errors->any())
            <div class="mb-4 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
                <p class="mb-1 font-semibold">Terdapat kesalahan:</p>
                <ul class="list-disc space-y-1 pl-5">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- Tombol Tambah User --}}
        <a
            href="{{ route('users.create') }}"
            class="mb-5 inline-block rounded-lg bg-gray-800 px-4 py-2.5
                text-sm font-semibold text-white
                transition hover:bg-gray-600
                focus:outline-none focus:ring-2 focus:ring-gray-400 focus:ring-offset-2"
        >
            + Tambah User Baru
        </a>

        {{-- Tabel User --}}
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead>
                    <tr class="border-b-2 border-gray-100">
                        <th class="py-3 pr-4 font-semibold text-gray-700">Nama</th>
                        <th class="py-3 pr-4 font-semibold text-gray-700">Email</th>
                        <th class="py-3 pr-4 font-semibold text-gray-700">Hak Akses (Role)</th>
                        <th class="py-3 pr-4 font-semibold text-gray-700">Aksi</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse ($users as $user)
                        <tr class="border-b border-gray-100">
                            <td class="py-3 pr-4 font-semibold text-gray-800">{{ $user->name }}</td>
                            <td class="py-3 pr-4 text-gray-600">{{ $user->email }}</td>

                            <td class="py-3 pr-4">
                                <span
                                    class="rounded px-2 py-1 text-xs font-bold
                                        {{ $user->role === 'superadmin'
                                            ? 'bg-sky-50 text-sky-600'
                                            : 'bg-gray-100 text-gray-600' }}"
                                >
                                    {{ strtoupper($user->role) }}
                                </span>
                            </td>

                            <td class="py-3 pr-4">
                                @if (auth()->id() != $user->id)
                                    <form
                                        action="{{ route('users.destroy', $user->id) }}"
                                        method="POST"
                                        onsubmit="return confirm('Yakin ingin menghapus user ini?');"
                                    >
                                        @csrf
                                        @method('DELETE')

                                        <button type="submit" class="font-medium text-red-500 hover:text-red-700">
                                            Hapus
                                        </button>
                                    </form>
                                @else
                                    <span class="text-xs text-gray-400">Akun Anda</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="py-6 text-center text-sm text-gray-500">
                                Belum ada data pengguna.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

    </div>

@endsection