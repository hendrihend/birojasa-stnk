@extends ('layouts.app')
@section ('title', 'Edit Klien')
@section ('header_title', 'Edit Data Klien')
@section ('content')
    <div class="mx-auto max-w-5xl">
        
        {{-- ERROR VALIDATION --}}

        @if ($errors->any())
            <div class="mb-6 rounded-xl border border-red-200 bg-red-50 p-4">
                <div class="flex items-start gap-3">
                    <div class="text-lg text-red-500">⚠</div>

                    <div>
                        <h3 class="text-sm font-semibold text-red-700">Terdapat kesalahan pada data</h3>
                        <ul class="mt-2 list-inside list-disc text-sm text-red-600">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>

        @endif
        {{-- FORM --}}
        <form action="{{ route('clients.update', $client->id) }}" method="POST" enctype="multipart/form-data">
            @csrf

            @method ('PUT')

        {{-- CARD --}}
        <div class="overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm">
            {{-- Header Card --}}
            <div class="border-b border-gray-200 px-6 py-5">
                <h2 class="text-lg font-semibold text-gray-800">Informasi Klien</h2>
                <p class="mt-1 text-sm text-gray-500">Perbarui informasi data klien dan dokumen KTP.</p>
            </div>

        {{-- FORM CONTENT --}}
            <div class="p-6">
                <div class="grid grid-cols-1 gap-8">
                    {{-- DATA KLIEN --}}
                        <div class="space-y-5">
                            {{-- Nama Lengkap --}}
                            <div>
                                <label for="nama_lengkap" class="mb-2 block text-sm font-medium text-gray-700">Nama Lengkap <span class="text-red-500">*</span></label>
                                <input id="nama_lengkap" type="text" name="nama_lengkap" value="{{ old('nama_lengkap', $client->nama_lengkap) }}" required class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm transition focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500"/>

                                @error ('nama_lengkap')
                                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- NIK --}}
                            <div>
                                <label for="nik" class="mb-2 block text-sm font-medium text-gray-700">NIK KTP</label>
                                <input id="nik" type="text" name="nik" value="{{ old('nik', $client->nik) }}" required maxlength="16" class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm transition focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500"/>

                                @error ('nik')
                                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Nomor WhatsApp --}}
                            <div>
                                <label for="no_whatsapp" class="mb-2 block text-sm font-medium text-gray-700">Nomor WhatsApp</label>
                                <input id="no_whatsapp" type="text" name="no_whatsapp" value="{{ old('no_whatsapp', $client->no_whatsapp) }}" class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm transition focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500"/>

                                @error ('no_whatsapp')
                                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>

                                @enderror
                            </div>

                            {{-- Alamat --}}
                            <div>
                                <label for="alamat" class="mb-2 block text-sm font-medium text-gray-700">Alamat Domisili</label>
                                <textarea id="alamat" name="alamat" rows="5" class="w-full resize-none rounded-lg border border-gray-300 px-4 py-2.5 text-sm transition focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500">{{ old('alamat', $client->alamat) }}</textarea>

                                @error ('alamat')
                                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>

                                @enderror
                            </div>
                        </div>
                        
                        {{-- FOOTER BUTTON --}}
                        <div class="flex flex-col-reverse gap-3 border-t border-gray-200 bg-gray-50 px-6 py-4 sm:flex-row sm:justify-end">
                            {{-- Batal --}}
                            <a href="{{ route('clients.index') }}" class="inline-flex items-center justify-center rounded-lg border border-gray-300 bg-white px-5 py-2.5 text-sm font-medium text-gray-600 transition hover:bg-gray-100">Batal</a>

                            {{-- Update --}}
                            <button type="submit" class="inline-flex items-center justify-center gap-2 rounded-lg bg-indigo-600 px-5 py-2.5 text-sm font-medium text-white transition hover:bg-indigo-700">
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>

                                Update Data
                            </button>
                        </div>
                </div>
            </div>
        </form>
    </div>

    </script>

@endsection
