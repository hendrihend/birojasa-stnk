@extends('layouts.app')
@section('title', 'Dokumen Kendaraan')
@section('header_title', 'Arsip Dokumen: ' . $vehicle->nopol)
@section('content')

<div class="space-y-6">
    {{-- Tombol Kembali --}}
    <div>
        <a href="{{ route('vehicles.index') }}" class="inline-flex items-center gap-2 rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm font-semibold text-gray-700 shadow-sm transition hover:bg-gray-50">
            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            Kembali ke Daftar Kendaraan
        </a>
    </div>


    {{-- Pesan sukses --}}
    @if(session('success'))
    <div class="flex items-center gap-3 rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-700">
        <svg class="h-5 w-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
        </svg>
        
        <span>{{ session('success') }}</span>
    </div>
    @endif


    {{-- Pesan error --}}
    @if($errors->any())
    <div class="rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
        
        <div class="mb-2 flex items-center gap-2 font-semibold">

                <svg
                    class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M12 9v2m0 4h.01M5.07 19h13.86a2 2 0 001.73-3L13.73 4a2 2 0 00-3.46 0L3.34 16a2 2 0 001.73 3z"
                    />
                </svg>

                Terjadi kesalahan
            </div>

            <ul class="list-disc space-y-1 pl-6">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>

        </div>
    @endif


    {{-- Informasi kendaraan --}}
    <div class="rounded-xl border border-indigo-100 bg-indigo-50 px-5 py-4">

        <div class="flex items-center gap-3">

            <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-indigo-100 text-indigo-600">

                <svg
                    class="h-6 w-6"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24"
                >
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M8 17h8m-9-4h10M5 17h-.5A2.5 2.5 0 012 14.5v-4A2.5 2.5 0 014.5 8H6l1.5-3h9L18 8h1.5a2.5 2.5 0 012.5 2.5v4a2.5 2.5 0 01-2.5 2.5H19M7 17a2 2 0 104 0m2 0a2 2 0 104 0"
                    />
                </svg>

            </div>

            <div>
                <p class="text-xs font-medium uppercase tracking-wide text-indigo-500">
                    Kendaraan
                </p>

                <p class="text-lg font-bold text-gray-800">
                    {{ $vehicle->nopol }}
                </p>
            </div>

        </div>

    </div>


    {{-- Layout utama --}}
    <div class="grid grid-cols-1 gap-6 xl:grid-cols-3">

        {{-- BAGIAN KIRI : FORM UPLOAD --}}
        <div class="xl:col-span-1">

            <div class="rounded-xl border border-gray-200 bg-white shadow-sm">

                {{-- Header --}}
                <div class="border-b border-gray-200 px-5 py-4">

                    <div class="flex items-center gap-3">

                        <div class="flex h-9 w-9 items-center justify-center rounded-lg bg-indigo-100 text-indigo-600">

                            <svg
                                class="h-5 w-5"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M12 16V4m0 0L8 8m4-4l4 4M5 20h14"
                                />
                            </svg>

                        </div>

                        <div>

                            <h3 class="font-semibold text-gray-800">
                                Unggah Dokumen Baru
                            </h3>

                            <p class="mt-0.5 text-xs text-gray-500">
                                Tambahkan dokumen kendaraan
                            </p>

                        </div>

                    </div>

                </div>


                {{-- Form --}}
                <div class="p-5">

                    <form
                        action="{{ route('documents.store', $vehicle->id) }}"
                        method="POST"
                        enctype="multipart/form-data"
                        class="space-y-5"
                    >

                        @csrf


                        {{-- Jenis Dokumen --}}
                        <div>

                            <label
                                for="jenis_dokumen"
                                class="mb-2 block text-sm font-semibold text-gray-700"
                            >
                                Jenis Dokumen
                                <span class="text-red-500">*</span>
                            </label>

                            <select
                                name="jenis_dokumen"
                                id="jenis_dokumen"
                                required
                                class="block w-full rounded-lg border border-gray-300 bg-white px-3 py-2.5 text-sm text-gray-700 shadow-sm outline-none transition focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200"
                            >

                                <option value="">
                                    -- Pilih Jenis Dokumen --
                                </option>

                                <option value="KTP">
                                    KTP (Pemilik)
                                </option>

                                <option value="STNK">
                                    STNK (Asli/Fotokopi)
                                </option>

                                <option value="BPKB">
                                    BPKB
                                </option>

                                <option value="Faktur">
                                    Faktur Kendaraan
                                </option>

                                <option value="Kwitansi">
                                    Kwitansi Pembelian
                                </option>

                                <option value="Surat Jalan">
                                    Surat Jalan / Pengantar
                                </option>

                            </select>

                        </div>


                        {{-- File --}}
                        <div>

                            <label
                                for="file_dokumen"
                                class="mb-2 block text-sm font-semibold text-gray-700"
                            >
                                Pilih File
                                <span class="text-red-500">*</span>
                            </label>

                            <div class="rounded-lg border-2 border-dashed border-gray-300 bg-gray-50 p-4 transition hover:border-indigo-400 hover:bg-indigo-50">

                                <input
                                    type="file"
                                    name="file_dokumen"
                                    id="file_dokumen"
                                    accept=".jpg,.jpeg,.png,.pdf"
                                    required
                                    class="block w-full cursor-pointer text-sm text-gray-600 file:mr-4 file:rounded-lg file:border-0 file:bg-indigo-50 file:px-4 file:py-2 file:text-sm file:font-semibold file:text-indigo-700 hover:file:bg-indigo-100"
                                >

                                <div class="mt-2 flex items-center gap-2 text-xs text-gray-500">

                                    <svg
                                        class="h-4 w-4"
                                        fill="none"
                                        stroke="currentColor"
                                        viewBox="0 0 24 24"
                                    >
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="2"
                                            d="M13 16h-1v-4h-1m1-8h.01M12 22a10 10 0 100-20 10 10 0 000 20z"
                                        />
                                    </svg>

                                    <span>
                                        Format JPG, PNG atau PDF. Maksimal 2MB.
                                    </span>

                                </div>

                            </div>

                        </div>


                        {{-- Tombol Upload --}}
                        <button
                            type="submit"
                            class="inline-flex w-full items-center justify-center gap-2 rounded-lg bg-indigo-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2"
                        >

                            <svg
                                class="h-5 w-5"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M12 16V4m0 0L8 8m4-4l4 4M5 20h14"
                                />
                            </svg>

                            Upload File

                        </button>

                    </form>

                </div>

            </div>

        </div>


        {{-- ========================================= --}}
        {{-- BAGIAN KANAN : DAFTAR DOKUMEN --}}
        {{-- ========================================= --}}
        <div class="xl:col-span-2">

            <div class="overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm">


                {{-- Header --}}
                <div class="border-b border-gray-200 px-5 py-4">

                    <div class="flex items-center justify-between">

                        <div class="flex items-center gap-3">

                            <div class="flex h-9 w-9 items-center justify-center rounded-lg bg-gray-100 text-gray-600">

                                <svg
                                    class="h-5 w-5"
                                    fill="none"
                                    stroke="currentColor"
                                    viewBox="0 0 24 24"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h7l5 5v11a2 2 0 01-2 2z"
                                    />
                                </svg>

                            </div>

                            <div>

                                <h3 class="font-semibold text-gray-800">
                                    Daftar Dokumen Tersimpan
                                </h3>

                                <p class="mt-0.5 text-xs text-gray-500">
                                    Dokumen kendaraan yang telah diunggah
                                </p>

                            </div>

                        </div>


                        {{-- Jumlah dokumen --}}
                        <span class="rounded-full bg-gray-100 px-3 py-1 text-xs font-semibold text-gray-600">
                            {{ $documents->count() }} Dokumen
                        </span>

                    </div>

                </div>


                {{-- Table --}}
                <div class="overflow-x-auto">

                    <table class="min-w-full text-left text-sm">

                        <thead class="border-b border-gray-200 bg-gray-50">

                            <tr>

                                <th class="whitespace-nowrap px-5 py-3.5 font-semibold text-gray-700">
                                    Jenis Dokumen
                                </th>

                                <th class="whitespace-nowrap px-5 py-3.5 font-semibold text-gray-700">
                                    Preview
                                </th>

                                <th class="whitespace-nowrap px-5 py-3.5 font-semibold text-gray-700">
                                    Tanggal Upload
                                </th>

                                <th class="whitespace-nowrap px-5 py-3.5 text-center font-semibold text-gray-700">
                                    Aksi
                                </th>

                            </tr>

                        </thead>


                        <tbody class="divide-y divide-gray-100">

                            @forelse($documents as $doc)

                                <tr class="transition hover:bg-gray-50">

                                    {{-- Jenis Dokumen --}}
                                    <td class="px-5 py-4">

                                        <div class="flex items-center gap-3">

                                            <div class="flex h-9 w-9 items-center justify-center rounded-lg bg-indigo-50 text-indigo-600">

                                                <svg
                                                    class="h-5 w-5"
                                                    fill="none"
                                                    stroke="currentColor"
                                                    viewBox="0 0 24 24"
                                                >
                                                    <path
                                                        stroke-linecap="round"
                                                        stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h7l5 5v11a2 2 0 01-2 2z"
                                                    />
                                                </svg>

                                            </div>

                                            <span class="font-semibold text-gray-800">
                                                {{ $doc->jenis_dokumen }}
                                            </span>

                                        </div>

                                    </td>


                                    {{-- Preview --}}
                                    <td class="px-5 py-4">

                                        <a
                                            href="{{ asset('storage/' . $doc->file_path) }}"
                                            target="_blank"
                                            class="inline-flex items-center gap-1.5 rounded-lg bg-indigo-50 px-3 py-2 text-xs font-semibold text-indigo-600 transition hover:bg-indigo-100 hover:text-indigo-700"
                                        >

                                            Lihat File

                                            <svg
                                                class="h-4 w-4"
                                                fill="none"
                                                stroke="currentColor"
                                                viewBox="0 0 24 24"
                                            >
                                                <path
                                                    stroke-linecap="round"
                                                    stroke-linejoin="round"
                                                    stroke-width="2"
                                                    d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4m-6-6l8-8m0 0v5m0-5h-5"
                                                />
                                            </svg>

                                        </a>

                                    </td>


                                    {{-- Tanggal Upload --}}
                                    <td class="whitespace-nowrap px-5 py-4 text-sm text-gray-500">

                                        {{ $doc->created_at->format('d M Y') }}

                                        <div class="mt-0.5 text-xs text-gray-400">
                                            {{ $doc->created_at->format('H:i') }}
                                        </div>

                                    </td>


                                    {{-- Aksi --}}
                                    <td class="px-5 py-4 text-center">

                                        <form
                                            action="{{ route('documents.destroy', $doc->id) }}"
                                            method="POST"
                                            onsubmit="return confirm('Yakin ingin menghapus dokumen ini secara permanen?');"
                                        >

                                            @csrf
                                            @method('DELETE')

                                            <button
                                                type="submit"
                                                class="inline-flex items-center gap-1.5 rounded-lg px-3 py-2 text-xs font-semibold text-red-600 transition hover:bg-red-50 hover:text-red-700"
                                            >

                                                <svg
                                                    class="h-4 w-4"
                                                    fill="none"
                                                    stroke="currentColor"
                                                    viewBox="0 0 24 24"
                                                >
                                                    <path
                                                        stroke-linecap="round"
                                                        stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6M9 7V4a1 1 0 011-1h4a1 1 0 011 1v3m-7 0h10"
                                                    />
                                                </svg>

                                                Hapus

                                            </button>

                                        </form>

                                    </td>

                                </tr>

                            @empty

                                <tr>

                                    <td
                                        colspan="4"
                                        class="px-5 py-12 text-center"
                                    >

                                        <div class="flex flex-col items-center">

                                            <div class="mb-3 flex h-12 w-12 items-center justify-center rounded-full bg-gray-100">

                                                <svg
                                                    class="h-6 w-6 text-gray-400"
                                                    fill="none"
                                                    stroke="currentColor"
                                                    viewBox="0 0 24 24"
                                                >
                                                    <path
                                                        stroke-linecap="round"
                                                        stroke-linejoin="round"
                                                        stroke-width="1.5"
                                                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h7l5 5v11a2 2 0 01-2 2z"
                                                    />
                                                </svg>

                                            </div>

                                            <p class="text-sm font-medium text-gray-500">
                                                Belum ada dokumen
                                            </p>

                                            <p class="mt-1 text-xs text-gray-400">
                                                Belum ada dokumen yang diunggah untuk kendaraan ini.
                                            </p>

                                        </div>

                                    </td>

                                </tr>

                            @endforelse

                        </tbody>

                    </table>

                </div>

            </div>

        </div>

    </div>

</div>

@endsection