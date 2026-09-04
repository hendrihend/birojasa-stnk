@extends('layouts.app')

@section('title', 'Tambah Klien')
@section('header_title', 'Tambah Data Klien Baru')

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

        <form action="{{ route('clients.store') }}" method="POST">
            @csrf
            
            <div class="space-y-5">
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-1">Nama Lengkap <span class="text-red-500">*</span></label>
                    <input type="text" name="nama_lengkap" value="{{ old('nama_lengkap') }}" required placeholder="Contoh: Budi Santoso" 
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-shadow bg-gray-50 focus:bg-white">
                </div>

                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-1">NIK (Nomor Induk Kependudukan)</label>
                    <input type="number" name="nik" value="{{ old('nik') }}" placeholder="16 Digit NIK KTP" 
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-shadow bg-gray-50 focus:bg-white">
                </div>

                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-1">Nomor WhatsApp <span class="text-red-500">*</span></label>
                    <input type="text" name="no_whatsapp" value="{{ old('no_whatsapp') }}" required placeholder="Contoh: 08123456789" 
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-shadow bg-gray-50 focus:bg-white">
                </div>

                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-1">Email <span class="text-red-500">*</span></label>
                    <input type="mail" name="email" value="{{ old('email') }}" required placeholder="Contoh: klien@gmail.com" 
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-shadow bg-gray-50 focus:bg-white">
                </div>

                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-1">Alamat Lengkap</label>
                    <textarea name="alamat" rows="3" placeholder="Alamat sesuai KTP atau domisili" 
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-shadow bg-gray-50 focus:bg-white resize-none">{{ old('alamat') }}</textarea>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="mt-8 flex gap-3">
                <button type="submit" class="px-6 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-lg shadow-sm transition-colors flex items-center gap-2">
                    <i class="fa-solid fa-floppy-disk"></i> Simpan Data
                </button>
                <a href="{{ route('clients.index') }}" class="px-6 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 font-bold rounded-lg transition-colors">
                    Batal
                </a>
            </div>
        </form>
    </div>
@endsection
