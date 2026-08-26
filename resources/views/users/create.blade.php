@extends('layouts.app')

@section('title', 'Tambah User')
@section('header_title', 'Tambah Pengguna Baru')

@section('content')

    <div class="max-w-2xl mx-auto">

        {{-- Error Validation --}}
        @if ($errors->any())
            <div class="mb-6 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
                <p class="mb-2 font-semibold">Terdapat kesalahan:</p>
                <ul class="list-disc space-y-1 pl-5">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- Form --}}
        <form
            action="{{ route('users.store') }}"
            method="POST"
            class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm"
        >
            @csrf

            {{-- Nama --}}
            <div class="mb-5">
                <label for="name" class="mb-2 block text-sm font-semibold text-gray-700">
                    Nama Lengkap <span class="text-red-500">*</span>
                </label>

                <input
                    type="text"
                    id="name"
                    name="name"
                    value="{{ old('name') }}"
                    required
                    autofocus
                    placeholder="Masukkan nama lengkap"
                    class="block w-full rounded-lg border border-gray-300 px-3 py-2.5
                        text-sm text-gray-700 shadow-sm
                        focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-200"
                >
            </div>

            {{-- Email --}}
            <div class="mb-5">
                <label for="email" class="mb-2 block text-sm font-semibold text-gray-700">
                    Email <span class="text-red-500">*</span>
                </label>

                <input
                    type="email"
                    id="email"
                    name="email"
                    value="{{ old('email') }}"
                    required
                    placeholder="Masukkan email"
                    class="block w-full rounded-lg border border-gray-300 px-3 py-2.5
                        text-sm text-gray-700 shadow-sm
                        focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-200"
                >
            </div>

            {{-- Password --}}
            <div class="mb-5">
                <label for="password" class="mb-2 block text-sm font-semibold text-gray-700">
                    Kata Sandi <span class="text-red-500">*</span>
                </label>

                <input
                    type="password"
                    id="password"
                    name="password"
                    required
                    placeholder="Masukkan kata sandi"
                    class="block w-full rounded-lg border border-gray-300 px-3 py-2.5
                        text-sm text-gray-700 shadow-sm
                        focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-200"
                >

                <p class="mt-1.5 text-xs text-gray-400">
                    Minimal 8 karakter.
                </p>
            </div>

            {{-- Konfirmasi Password --}}
            <div class="mb-5">
                <label for="password_confirmation" class="mb-2 block text-sm font-semibold text-gray-700">
                    Konfirmasi Kata Sandi <span class="text-red-500">*</span>
                </label>

                <input
                    type="password"
                    id="password_confirmation"
                    name="password_confirmation"
                    required
                    placeholder="Ulangi kata sandi"
                    class="block w-full rounded-lg border border-gray-300 px-3 py-2.5
                        text-sm text-gray-700 shadow-sm
                        focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-200"
                >
            </div>

            {{-- Role --}}
            <div class="mb-6">
                <label for="role" class="mb-2 block text-sm font-semibold text-gray-700">
                    Hak Akses (Role) <span class="text-red-500">*</span>
                </label>

                <select
                    id="role"
                    name="role"
                    required
                    class="block w-full rounded-lg border border-gray-300 bg-white px-3 py-2.5
                        text-sm text-gray-700 shadow-sm
                        focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-200"
                >
                    <option value="" disabled {{ old('role') ? '' : 'selected' }}>
                        -- Pilih Role --
                    </option>

                    <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>
                        Admin
                    </option>

                    <option value="superadmin" {{ old('role') == 'superadmin' ? 'selected' : '' }}>
                        Superadmin
                    </option>
                </select>
            </div>

            {{-- Tombol --}}
            <div class="flex items-center justify-end gap-3 border-t border-gray-100 pt-5">
                
                <a
                    href="{{ route('users.index') }}"
                    class="rounded-lg border border-gray-300 bg-white px-4 py-2.5
                        text-sm font-medium text-gray-700
                        hover:bg-gray-50
                        focus:outline-none focus:ring-2 focus:ring-gray-200"
                >
                    Batal
                </a>

                <button
                    type="submit"
                    class="rounded-lg bg-indigo-600 px-5 py-2.5
                        text-sm font-semibold text-white shadow-sm
                        transition hover:bg-indigo-700
                        focus:outline-none focus:ring-2 focus:ring-indigo-300"
                >
                    Simpan User
                </button>
            </div>

        </form>

    </div>

@endsection