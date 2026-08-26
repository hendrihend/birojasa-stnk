@extends ('layouts.app')
@section ('title', 'Tambah Klien')
@section ('header_title', 'Tambah Data Klien')
@section ('content')
    <div class="mx-auto max-w-3xl">

        {{-- FORM CARD --}}

        <div class="overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm">
            {{-- Header --}}
            <div class="border-b border-gray-200 px-6 py-5">
                <h2 class="text-lg font-semibold text-gray-800">Informasi Klien</h2>

                <p class="mt-1 text-sm text-gray-500">Masukkan informasi klien baru.</p>
            </div>

            {{-- FORM --}}

            <form action="{{ route('clients.store') }}" method="POST">
                @csrf

                <div class="space-y-5 p-6">
                    {{-- NAMA LENGKAP --}}

                    <div>
                        <label for="nama_lengkap" class="mb-2 block text-sm font-medium text-gray-700">Nama Lengkap<span class="text-red-500">*</span></label>
                        <input id="nama_lengkap" type="text" name="nama_lengkap" value="{{ old('nama_lengkap') }}" required autofocus placeholder="Masukkan nama lengkap" class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-700 outline-none transition focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100"/>

                        @error ('nama_lengkap')
                            <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- NIK --}}
                    <div>
                        <label for="nik" class="mb-2 block text-sm font-medium text-gray-700">NIK KTP</label>
                        <input id="nik" type="text" name="nik" value="{{ old('nik') }}" required maxlength="16" placeholder="Masukkan NIK 16 digit" class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-700 outline-none transition focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100"/>

                        @error ('nik')
                            <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>

                        @enderror
                    </div>


                    {{-- WHATSAPP --}}
                    <div>
                        <label for="no_whatsapp" class="mb-2 block text-sm font-medium text-gray-700">Nomor WhatsApp</label>
                        <input id="no_whatsapp" type="text" name="no_whatsapp" value="{{ old('no_whatsapp') }}" placeholder="Contoh: 081234567890" class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-700 outline-none transition focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100"/>

                        @error ('no_whatsapp')
                            <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>

                        @enderror
                    </div>


                    {{-- ALAMAT --}}
                    <div>
                        <label for="alamat" class="mb-2 block text-sm font-medium text-gray-700">Alamat Domisili</label>
                        <textarea id="alamat" name="alamat" rows="4" placeholder="Masukkan alamat lengkap" class="w-full resize-none rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-700 outline-none transition focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100">{{ old('alamat') }}</textarea>

                        @error ('alamat')
                            <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>

                        @enderror
                    </div>
                </div>

                {{-- BUTTON --}}
                <div
                    class="flex items-center justify-end gap-3 border-t border-gray-200 bg-gray-50 px-6 py-4">
                    {{-- Batal --}}
                    <a href="{{ route('clients.index') }}" class="inline-flex w-auto items-center justify-center rounded-lg border border-gray-300 bg-white px-5 py-2.5 text-sm font-medium text-gray-600 transition hover:bg-gray-100">Batal</a>

                    {{-- Simpan --}}
                    <button type="submit" class="inline-flex w-auto items-center justify-center gap-2 rounded-lg bg-indigo-600 px-5 py-2.5 text-sm font-medium text-white transition hover:bg-indigo-700">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>

                        Simpan Data
                    </button>
                </div>
            </form>
        </div>
    </div>

@endsection
