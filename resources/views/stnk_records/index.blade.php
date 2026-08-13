@extends('layouts.app')

@section('title', 'Manajemen Pajak & STNK')
@section('header_title', 'Jadwal Jatuh Tempo Pajak')

@section('content')

<div class="space-y-6">

    {{-- Pesan sukses --}}
    @if(session('success'))
        <div class="flex items-center gap-3 rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-700">
            <svg class="h-5 w-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>

            <span>{{ session('success') }}</span>
        </div>
    @endif


    {{-- Header halaman --}}
    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">

        <div>
            <h2 class="text-lg font-semibold text-gray-800">
                Data Pajak & STNK
            </h2>

            <p class="mt-1 text-sm text-gray-500">
                Monitoring jadwal jatuh tempo pajak kendaraan.
            </p>
        </div>

        <a
            href="{{ route('stnk_records.create') }}"
            class="inline-flex items-center justify-center gap-2 rounded-lg bg-indigo-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-indigo-700"
        >
            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M12 4v16m8-8H4" />
            </svg>

            Tambah Data STNK/Pajak
        </a>

    </div>


    {{-- Filter --}}
    <div class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm">

        <div class="mb-4 flex items-center gap-2">
            <svg class="h-5 w-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M3 4h18M6 10h12M10 16h4M8 20h8" />
            </svg>

            <h3 class="text-sm font-semibold text-gray-800">
                Filter Data
            </h3>
        </div>


        <div class="grid grid-cols-1 gap-4 md:grid-cols-3">

            {{-- Search kendaraan --}}
            <div>
                <label
                    for="searchVehicle"
                    class="mb-2 block text-sm font-medium text-gray-700"
                >
                    Cari Kendaraan
                </label>

                <div class="relative">

                    <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                        <svg
                            class="h-5 w-5 text-gray-400"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="m21 21-4.35-4.35m0 0A7.5 7.5 0 1 0 6.04 6.04a7.5 7.5 0 0 0 10.61 10.61Z"
                            />
                        </svg>
                    </div>

                    <input
                        type="text"
                        id="searchVehicle"
                        placeholder="Nomor polisi atau nama klien..."
                        class="block w-full rounded-lg border border-gray-300 bg-white py-2.5 pl-10 pr-3 text-sm text-gray-700 outline-none transition focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200"
                    >

                </div>
            </div>


            {{-- Filter status --}}
            <div>
                <label
                    for="filterStatus"
                    class="mb-2 block text-sm font-medium text-gray-700"
                >
                    Status
                </label>

                <select
                    id="filterStatus"
                    class="block w-full rounded-lg border border-gray-300 bg-white px-3 py-2.5 text-sm text-gray-700 outline-none transition focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200"
                >
                    <option value="all">Semua Status</option>
                    <option value="aktif">Aktif</option>
                    <option value="nonaktif">Nonaktif</option>
                </select>
            </div>


            {{-- Filter jatuh tempo --}}
            <div>
                <label
                    for="filterTempo"
                    class="mb-2 block text-sm font-medium text-gray-700"
                >
                    Jatuh Tempo
                </label>

                <select
                    id="filterTempo"
                    class="block w-full rounded-lg border border-gray-300 bg-white px-3 py-2.5 text-sm text-gray-700 outline-none transition focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200"
                >
                    <option value="all">Semua</option>
                    <option value="terlewat">Sudah Terlewat</option>
                    <option value="30">≤ 30 Hari</option>
                    <option value="60">≤ 60 Hari</option>
                    <option value="90">≤ 90 Hari</option>
                </select>
            </div>

        </div>

    </div>


    {{-- Table --}}
    <div class="overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm">

        <div class="overflow-x-auto">

            <table class="min-w-full text-left text-sm">

                <thead class="border-b border-gray-200 bg-gray-50">

                    <tr>

                        <th class="whitespace-nowrap px-5 py-4 font-semibold text-gray-700">
                            Kendaraan
                        </th>

                        <th class="whitespace-nowrap px-5 py-4 font-semibold text-gray-700">
                            Nomor STNK
                        </th>

                        <th class="whitespace-nowrap px-5 py-4 font-semibold text-gray-700">
                            Jatuh Tempo Pajak
                        </th>

                        <th class="whitespace-nowrap px-5 py-4 font-semibold text-gray-700">
                            Jatuh Tempo Kaleng
                        </th>

                        <th class="whitespace-nowrap px-5 py-4 font-semibold text-gray-700">
                            Status
                        </th>

                        <th class="whitespace-nowrap px-5 py-4 text-center font-semibold text-gray-700">
                            Aksi
                        </th>

                    </tr>

                </thead>


                <tbody class="divide-y divide-gray-100">

                    @forelse($records as $index => $record)

                        {{-- Perhitungan jatuh tempo --}}
                        @php

                            $tglPajak = \Carbon\Carbon::parse(
                                $record->tgl_jatuh_tempo_pajak
                            );

                            $hariIni = \Carbon\Carbon::now()->startOfDay();

                            $sisaHari = $hariIni->diffInDays(
                                $tglPajak,
                                false
                            );

                        @endphp


                        {{-- Warna background berdasarkan jatuh tempo --}}
                        @php

                            $rowClass = '';

                            if ($sisaHari < 0) {

                                $rowClass = 'bg-red-50/50';

                            } elseif ($sisaHari <= 30) {

                                $rowClass = 'bg-yellow-50/50';

                            }

                        @endphp


                        <tr
                            class="{{ $rowClass }} transition hover:bg-gray-50"
                            data-search="{{ strtolower(($record->vehicle->nopol ?? '') . ' ' . ($record->vehicle->client->nama_lengkap ?? '')) }}"
                            data-status="{{ $record->status_aktif ? 'aktif' : 'nonaktif' }}"
                            data-tempo="{{ $sisaHari }}"
                        >

                            {{-- Kendaraan --}}
                            <td class="px-5 py-4">

                                <div class="font-semibold uppercase text-gray-800">
                                    {{ $record->vehicle->nopol ?? '-' }}
                                </div>

                                <div class="mt-1 text-xs text-gray-500">
                                    {{ $record->vehicle->client->nama_lengkap ?? '-' }}
                                </div>

                            </td>


                            {{-- Nomor STNK --}}
                            <td class="whitespace-nowrap px-5 py-4 text-gray-600">
                                {{ $record->no_stnk ?? '-' }}
                            </td>


                            {{-- Jatuh Tempo Pajak --}}
                            <td class="px-5 py-4">

                                @if($sisaHari < 0)

                                    <div class="font-semibold text-red-600">
                                        {{ $tglPajak->format('d M Y') }}
                                    </div>

                                    <div class="mt-1 text-xs font-medium text-red-500">
                                        Terlewat {{ abs($sisaHari) }} hari
                                    </div>

                                @elseif($sisaHari == 0)

                                    <div class="font-semibold text-red-600">
                                        {{ $tglPajak->format('d M Y') }}
                                    </div>

                                    <div class="mt-1 text-xs font-semibold text-red-500">
                                        Hari ini!
                                    </div>

                                @elseif($sisaHari <= 30)

                                    <div class="font-semibold text-yellow-600">
                                        {{ $tglPajak->format('d M Y') }}
                                    </div>

                                    <div class="mt-1 text-xs font-medium text-yellow-600">
                                        {{ $sisaHari }} hari lagi
                                    </div>

                                @else

                                    <div class="font-semibold text-green-600">
                                        {{ $tglPajak->format('d M Y') }}
                                    </div>

                                    <div class="mt-1 text-xs text-gray-500">
                                        {{ $sisaHari }} hari lagi
                                    </div>

                                @endif

                            </td>


                            {{-- Jatuh Tempo Kaleng --}}
                            <td class="whitespace-nowrap px-5 py-4 text-gray-600">

                                @if($record->tgl_habis_stnk)

                                    {{ \Carbon\Carbon::parse($record->tgl_habis_stnk)->format('d M Y') }}

                                @else

                                    -

                                @endif

                            </td>


                            {{-- Status --}}
                            <td class="px-5 py-4">

                                @if($record->status_aktif)

                                    <span class="inline-flex items-center rounded-full bg-green-100 px-2.5 py-1 text-xs font-semibold text-green-700">

                                        <span class="mr-1.5 h-1.5 w-1.5 rounded-full bg-green-500"></span>

                                        Aktif

                                    </span>

                                @else

                                    <span class="inline-flex items-center rounded-full bg-red-100 px-2.5 py-1 text-xs font-semibold text-red-700">

                                        <span class="mr-1.5 h-1.5 w-1.5 rounded-full bg-red-500"></span>

                                        Nonaktif

                                    </span>

                                @endif

                            </td>


                            {{-- Aksi --}}
                            <td class="px-5 py-4 text-center">

                                <form
                                    action="{{ route('stnk_records.destroy', $record->id) }}"
                                    method="POST"
                                    onsubmit="return confirm('Yakin ingin menghapus data STNK ini?');"
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
                                colspan="6"
                                class="px-5 py-12 text-center"
                            >

                                <div class="flex flex-col items-center">

                                    <svg
                                        class="mb-3 h-12 w-12 text-gray-300"
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

                                    <p class="text-sm font-medium text-gray-500">
                                        Belum ada data STNK/Pajak.
                                    </p>

                                </div>

                            </td>

                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>


        {{-- Footer jumlah data --}}
        @if($records->count() > 0)

            <div class="border-t border-gray-200 bg-gray-50 px-5 py-3">

                <p class="text-sm text-gray-500">
                    Menampilkan
                    <span class="font-semibold text-gray-700">
                        {{ $records->count() }}
                    </span>
                    data STNK/Pajak
                </p>

            </div>

        @endif

    </div>

</div>


{{-- Javascript untuk filter frontend --}}
<script>

document.addEventListener('DOMContentLoaded', function () {

    const searchInput = document.getElementById('searchVehicle');
    const statusFilter = document.getElementById('filterStatus');
    const tempoFilter = document.getElementById('filterTempo');

    const rows = document.querySelectorAll(
        'tbody tr[data-search]'
    );


    function filterTable() {

        const searchValue = searchInput.value
            .toLowerCase()
            .trim();

        const statusValue = statusFilter.value;
        const tempoValue = tempoFilter.value;


        rows.forEach(function (row) {

            const searchData = row.dataset.search;
            const statusData = row.dataset.status;
            const tempoData = parseInt(row.dataset.tempo);


            // Filter pencarian
            const matchSearch =
                searchData.includes(searchValue);


            // Filter status
            const matchStatus =
                statusValue === 'all' ||
                statusData === statusValue;


            // Filter jatuh tempo
            let matchTempo = true;


            if (tempoValue === 'terlewat') {

                matchTempo = tempoData < 0;

            } else if (tempoValue === '30') {

                matchTempo = tempoData >= 0 &&
                             tempoData <= 30;

            } else if (tempoValue === '60') {

                matchTempo = tempoData >= 0 &&
                             tempoData <= 60;

            } else if (tempoValue === '90') {

                matchTempo = tempoData >= 0 &&
                             tempoData <= 90;

            }


            // Tampilkan / sembunyikan
            if (
                matchSearch &&
                matchStatus &&
                matchTempo
            ) {

                row.classList.remove('hidden');

            } else {

                row.classList.add('hidden');

            }

        });

    }


    searchInput.addEventListener(
        'input',
        filterTable
    );

    statusFilter.addEventListener(
        'change',
        filterTable
    );

    tempoFilter.addEventListener(
        'change',
        filterTable
    );

});

</script>

@endsection