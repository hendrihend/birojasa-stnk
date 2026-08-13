@extends('layouts.app')
@section('title', 'Edit Klien')
@section('header_title', 'Edit Data Klien')
@section('content')

<div class="max-w-5xl mx-auto">

    {{-- ================================================= --}}
    {{-- ERROR VALIDATION --}}
    {{-- ================================================= --}}

    @if($errors->any())

        <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-xl">

            <div class="flex items-start gap-3">

                <div class="text-red-500 text-lg">
                    ⚠
                </div>

                <div>

                    <h3 class="text-sm font-semibold text-red-700">
                        Terdapat kesalahan pada data
                    </h3>

                    <ul class="mt-2 text-sm text-red-600 list-disc list-inside">

                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach

                    </ul>

                </div>

            </div>

        </div>

    @endif


    {{-- ================================================= --}}
    {{-- FORM --}}
    {{-- ================================================= --}}

    <form
        action="{{ route('clients.update', $client->id) }}"
        method="POST"
        enctype="multipart/form-data"
    >

        @csrf

        @method('PUT')


        {{-- ================================================= --}}
        {{-- CARD --}}
        {{-- ================================================= --}}

        <div class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden">


            {{-- Header Card --}}
            <div class="px-6 py-5 border-b border-gray-200">

                <h2 class="text-lg font-semibold text-gray-800">
                    Informasi Klien
                </h2>

                <p class="text-sm text-gray-500 mt-1">
                    Perbarui informasi data klien dan dokumen KTP.
                </p>

            </div>


            {{-- ================================================= --}}
            {{-- FORM CONTENT --}}
            {{-- ================================================= --}}

            <div class="p-6">

                <div class="grid grid-cols-1 gap-8">


                    {{-- ================================================= --}}
                    {{-- DATA KLIEN --}}
                    {{-- ================================================= --}}

                    <div class="space-y-5">

                        {{-- Nama Lengkap --}}
                        <div>

                            <label
                                for="nama_lengkap"
                                class="block text-sm font-medium text-gray-700 mb-2"
                            >
                                Nama Lengkap
                                <span class="text-red-500">*</span>
                            </label>

                            <input
                                id="nama_lengkap"
                                type="text"
                                name="nama_lengkap"
                                value="{{ old('nama_lengkap', $client->nama_lengkap) }}"
                                required
                                class="
                                    w-full
                                    px-4 py-2.5
                                    text-sm
                                    border border-gray-300
                                    rounded-lg
                                    focus:outline-none
                                    focus:ring-2
                                    focus:ring-indigo-500
                                    focus:border-indigo-500
                                    transition
                                "
                            >

                            @error('nama_lengkap')

                                <p class="mt-1 text-xs text-red-600">
                                    {{ $message }}
                                </p>

                            @enderror

                        </div>


                        {{-- NIK --}}
                        <div>

                            <label
                                for="nik"
                                class="block text-sm font-medium text-gray-700 mb-2"
                            >
                                NIK KTP
                            </label>

                            <input
                                id="nik"
                                type="text"
                                name="nik"
                                value="{{ old('nik', $client->nik) }}"
                                required
                                maxlength="16"
                                class="
                                    w-full
                                    px-4 py-2.5
                                    text-sm
                                    border border-gray-300
                                    rounded-lg
                                    focus:outline-none
                                    focus:ring-2
                                    focus:ring-indigo-500
                                    focus:border-indigo-500
                                    transition
                                "
                            >

                            @error('nik')

                                <p class="mt-1 text-xs text-red-600">
                                    {{ $message }}
                                </p>

                            @enderror

                        </div>


                        {{-- Nomor WhatsApp --}}
                        <div>

                            <label
                                for="no_whatsapp"
                                class="block text-sm font-medium text-gray-700 mb-2"
                            >
                                Nomor WhatsApp
                            </label>

                            <input
                                id="no_whatsapp"
                                type="text"
                                name="no_whatsapp"
                                value="{{ old('no_whatsapp', $client->no_whatsapp) }}"
                                class="
                                    w-full
                                    px-4 py-2.5
                                    text-sm
                                    border border-gray-300
                                    rounded-lg
                                    focus:outline-none
                                    focus:ring-2
                                    focus:ring-indigo-500
                                    focus:border-indigo-500
                                    transition
                                "
                            >

                            @error('no_whatsapp')

                                <p class="mt-1 text-xs text-red-600">
                                    {{ $message }}
                                </p>

                            @enderror

                        </div>


                        {{-- Alamat --}}
                        <div>

                            <label
                                for="alamat"
                                class="block text-sm font-medium text-gray-700 mb-2"
                            >
                                Alamat Domisili
                            </label>

                            <textarea
                                id="alamat"
                                name="alamat"
                                rows="5"
                                class="
                                    w-full
                                    px-4 py-2.5
                                    text-sm
                                    border border-gray-300
                                    rounded-lg
                                    resize-none
                                    focus:outline-none
                                    focus:ring-2
                                    focus:ring-indigo-500
                                    focus:border-indigo-500
                                    transition
                                "
                            >{{ old('alamat', $client->alamat) }}</textarea>

                            @error('alamat')

                                <p class="mt-1 text-xs text-red-600">
                                    {{ $message }}
                                </p>

                            @enderror

                        </div>

                    </div>


            {{-- ================================================= --}}
            {{-- FOOTER BUTTON --}}
            {{-- ================================================= --}}

            <div
                class="
                    px-6 py-4
                    bg-gray-50
                    border-t border-gray-200
                    flex flex-col-reverse
                    sm:flex-row
                    sm:justify-end
                    gap-3
                "
            >

                {{-- Batal --}}
                <a
                    href="{{ route('clients.index') }}"
                    class="
                        inline-flex
                        items-center
                        justify-center
                        px-5 py-2.5
                        text-sm
                        font-medium
                        text-gray-600
                        bg-white
                        border border-gray-300
                        rounded-lg
                        hover:bg-gray-100
                        transition
                    "
                >
                    Batal
                </a>


                {{-- Update --}}
                <button
                    type="submit"
                    class="
                        inline-flex
                        items-center
                        justify-center
                        gap-2
                        px-5 py-2.5
                        text-sm
                        font-medium
                        text-white
                        bg-indigo-600
                        hover:bg-indigo-700
                        rounded-lg
                        transition
                    "
                >

                    <svg
                        class="w-4 h-4"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M5 13l4 4L19 7"
                        />
                    </svg>

                    Update Data

                </button>

            </div>

        </div>

    </form>

</div>

</script>

@endsection