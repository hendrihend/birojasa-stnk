@extends('layouts.app')

@section('title', 'Tambah User Baru')
@section('header_title', 'Buat Akun Pengguna')

@section('content')
    <div class="max-w-2xl mx-auto bg-white p-8 rounded-xl shadow-sm border border-gray-100">

        <!-- Pesan Error -->
        @if($errors->any())
            <div class="mb-6 px-4 py-3 bg-red-50 border-l-4 border-red-500 text-red-700 rounded shadow-sm">
                <ul class="list-disc list-inside text-sm font-medium">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('users.store') }}" method="POST">
            @csrf

            <div class="space-y-5">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Nama Lengkap <span class="text-red-500">*</span></label>
                    <input type="text" name="name" value="{{ old('name') }}" required placeholder="Budi Santoso"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-shadow bg-gray-50 focus:bg-white">
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Email <span class="text-red-500">*</span></label>
                    <input type="email" name="email" value="{{ old('email') }}" required placeholder="nama@email.com"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-shadow bg-gray-50 focus:bg-white">
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Password <span class="text-red-500">*</span></label>
                    <input type="password" name="password" required minlength="8" placeholder="Minimal 8 karakter"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-shadow bg-gray-50 focus:bg-white">
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Hak Akses (Role) <span class="text-red-500">*</span></label>
                    <select name="role" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-shadow bg-gray-50 focus:bg-white">
                        <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Admin Biasa (Operasional)</option>
                        <option value="super_admin" {{ old('role') == 'super_admin' ? 'selected' : '' }}>Super Admin (Akses Penuh)</option>
                    </select>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="mt-8 flex gap-3">
                <button type="submit" class="px-6 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg shadow-sm transition-colors flex items-center gap-2">
                    <i class="fa-solid fa-floppy-disk"></i> Simpan Akun
                </button>
                <a href="{{ route('users.index') }}" class="px-6 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold rounded-lg transition-colors">
                    Batal
                </a>
            </div>
        </form>
    </div>
@endsection