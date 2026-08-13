@extends('layouts.app')
@section('title', 'Edit Klien')
@section('header_title', 'Tambah Data Klien')
@section('content')

<div class="max-w-3xl mx-auto">
    {{-- ERROR VALIDATION --}}

    @if($errors->any())

        <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-lg">

            <div class="flex items-start gap-3">

                <div class="text-red-500">
                    ⚠
                </div>

                <div>

                    <p class="text-sm font-semibold text-red-700">
                        Terdapat kesalahan pada data
                    </p>

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
    {{-- FORM CARD --}}
    {{-- ================================================= --}}

    <div class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden">


        {{-- Header --}}
        <div class="px-6 py-5 border-b border-gray-200">

            <h2 class="text-lg font-semibold text-gray-800">
                Informasi Klien
            </h2>

            <p class="mt-1 text-sm text-gray-500">
                Masukkan informasi klien baru.
            </p>

        </div>


        {{-- ================================================= --}}
        {{-- FORM --}}
        {{-- ================================================= --}}

        <form
            action="{{ route('clients.store') }}"
            method="POST"
        >

            @csrf


            <div class="p-6 space-y-5">


                {{-- ================================================= --}}
                {{-- NAMA LENGKAP --}}
                {{-- ================================================= --}}

                <div>

                    <label
                        for="nama_lengkap"
                        class="block mb-2 text-sm font-medium text-gray-700"
                    >
                        Nama Lengkap
                        <span class="text-red-500">*</span>
                    </label>

                    <input
                        id="nama_lengkap"
                        type="text"
                        name="nama_lengkap"
                        value="{{ old('nama_lengkap') }}"
                        required
                        autofocus
                        placeholder="Masukkan nama lengkap"
                        class="
                            w-full
                            px-4 py-2.5
                            text-sm
                            text-gray-700
                            bg-white
                            border border-gray-300
                            rounded-lg
                            outline-none
                            transition
                            focus:border-indigo-500
                            focus:ring-2
                            focus:ring-indigo-100
                        "
                    >

                    @error('nama_lengkap')

                        <p class="mt-1.5 text-xs text-red-600">
                            {{ $message }}
                        </p>

                    @enderror

                </div>


                {{-- ================================================= --}}
                {{-- NIK --}}
                {{-- ================================================= --}}

                <div>

                    <label
                        for="nik"
                        class="block mb-2 text-sm font-medium text-gray-700"
                    >
                        NIK KTP
                    </label>

                    <input
                        id="nik"
                        type="text"
                        name="nik"
                        value="{{ old('nik') }}"
                        required
                        maxlength="16"
                        placeholder="Masukkan NIK 16 digit"
                        class="
                            w-full
                            px-4 py-2.5
                            text-sm
                            text-gray-700
                            bg-white
                            border border-gray-300
                            rounded-lg
                            outline-none
                            transition
                            focus:border-indigo-500
                            focus:ring-2
                            focus:ring-indigo-100
                        "
                    >

                    @error('nik')

                        <p class="mt-1.5 text-xs text-red-600">
                            {{ $message }}
                        </p>

                    @enderror

                </div>


                {{-- ================================================= --}}
                {{-- WHATSAPP --}}
                {{-- ================================================= --}}

                <div>

                    <label
                        for="no_whatsapp"
                        class="block mb-2 text-sm font-medium text-gray-700"
                    >
                        Nomor WhatsApp
                    </label>

                    <input
                        id="no_whatsapp"
                        type="text"
                        name="no_whatsapp"
                        value="{{ old('no_whatsapp') }}"
                        placeholder="Contoh: 081234567890"
                        class="
                            w-full
                            px-4 py-2.5
                            text-sm
                            text-gray-700
                            bg-white
                            border border-gray-300
                            rounded-lg
                            outline-none
                            transition
                            focus:border-indigo-500
                            focus:ring-2
                            focus:ring-indigo-100
                        "
                    >

                    @error('no_whatsapp')

                        <p class="mt-1.5 text-xs text-red-600">
                            {{ $message }}
                        </p>

                    @enderror

                </div>


                {{-- ================================================= --}}
                {{-- ALAMAT --}}
                {{-- ================================================= --}}

                <div>

                    <label
                        for="alamat"
                        class="block mb-2 text-sm font-medium text-gray-700"
                    >
                        Alamat Domisili
                    </label>

                    <textarea
                        id="alamat"
                        name="alamat"
                        rows="4"
                        placeholder="Masukkan alamat lengkap"
                        class="
                            w-full
                            px-4 py-2.5
                            text-sm
                            text-gray-700
                            bg-white
                            border border-gray-300
                            rounded-lg
                            outline-none
                            resize-none
                            transition
                            focus:border-indigo-500
                            focus:ring-2
                            focus:ring-indigo-100
                        "
                    >{{ old('alamat') }}</textarea>

                    @error('alamat')

                        <p class="mt-1.5 text-xs text-red-600">
                            {{ $message }}
                        </p>

                    @enderror

                </div>

            </div>


            {{-- ================================================= --}}
            {{-- BUTTON --}}
            {{-- ================================================= --}}

            <div
                class="
                    px-6 py-4
                    bg-gray-50
                    border-t border-gray-200
                    flex
                    justify-end
                    items-center
                    gap-3
                "
            >

                {{-- Batal --}}
                <a
                    href="{{ route('clients.index') }}"
                    class="
                        inline-flex
                        w-auto
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


                {{-- Simpan --}}
                <button
                    type="submit"
                    class="
                        inline-flex
                        w-auto
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

                    Simpan Data

                </button>

            </div>

        </form>

    </div>
</div>

@endsection