@extends('layouts.app')

@section('title', 'Data Kendaraan')

@section('header_title', 'Manajemen Data Kendaraan')

@section('content')

<div class="space-y-6">

    {{-- ================================================= --}}
    {{-- SUCCESS MESSAGE --}}
    {{-- ================================================= --}}

    @if(session('success'))
        <div class="flex items-center gap-3 p-4 bg-green-50 border border-green-200 text-green-700 rounded-lg">

            <span class="text-lg">✓</span>

            <p class="text-sm font-medium">
                {{ session('success') }}
            </p>

        </div>
    @endif


    {{-- ================================================= --}}
    {{-- HEADER --}}
    {{-- ================================================= --}}

    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">

        <div>

            <h2 class="text-xl font-semibold text-gray-800">
                Data Kendaraan
            </h2>

            <p class="text-sm text-gray-500 mt-1">
                Kelola data kendaraan dan dokumen STNK klien.
            </p>

        </div>


        {{-- Tambah Kendaraan --}}
        <a
            href="{{ route('vehicles.create') }}"
            class="
                inline-flex
                items-center
                justify-center
                gap-2
                px-4 py-2.5
                bg-indigo-600
                hover:bg-indigo-700
                text-white
                text-sm
                font-medium
                rounded-lg
                transition
            "
        >

            <span class="text-lg leading-none">
                +
            </span>

            Tambah Kendaraan

        </a>

    </div>


    {{-- ================================================= --}}
    {{-- TABLE CARD --}}
    {{-- ================================================= --}}

    <div class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden">


        {{-- ================================================= --}}
        {{-- SEARCH --}}
        {{-- ================================================= --}}

        <div class="p-5 border-b border-gray-200">

            <div class="relative w-full lg:w-96">

                {{-- Search Icon --}}
                <div
                    class="
                        absolute
                        inset-y-0
                        left-0
                        flex
                        items-center
                        pl-3
                        pointer-events-none
                    "
                >

                    <svg
                        class="w-5 h-5 text-gray-400"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                    >

                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="m21 21-4.35-4.35m2.35-5.65a8 8 0 1 1-16 0 8 8 0 0 1 16 0Z"
                        />

                    </svg>

                </div>


                <input
                    type="text"
                    id="vehicleSearch"
                    placeholder="Cari nomor polisi, klien, merk..."
                    class="
                        w-full
                        pl-10
                        pr-4
                        py-2.5
                        text-sm
                        border
                        border-gray-300
                        rounded-lg
                        focus:outline-none
                        focus:ring-2
                        focus:ring-indigo-500
                        focus:border-indigo-500
                    "
                >

            </div>

        </div>


        {{-- ================================================= --}}
        {{-- TABLE --}}
        {{-- ================================================= --}}

        <div class="overflow-x-auto">

            <table class="w-full text-sm text-left">

                <thead class="bg-gray-50 border-b border-gray-200">

                    <tr>

                        <th class="px-6 py-4 font-semibold text-gray-600">
                            No
                        </th>

                        <th class="px-6 py-4 font-semibold text-gray-600">
                            Nomor Polisi
                        </th>

                        <th class="px-6 py-4 font-semibold text-gray-600">
                            Nama Klien
                        </th>

                        <th class="px-6 py-4 font-semibold text-gray-600">
                            Nama Pemilik
                        </th>

                        <th class="px-6 py-4 font-semibold text-gray-600">
                            Merk & Tipe
                        </th>

                        <th class="px-6 py-4 font-semibold text-gray-600">
                            Tahun
                        </th>

                        <th class="px-6 py-4 text-center font-semibold text-gray-600">
                            Aksi
                        </th>

                    </tr>

                </thead>


                <tbody
                    id="vehicleTableBody"
                    class="divide-y divide-gray-100"
                >

                    @forelse($vehicles as $index => $vehicle)

                        <tr
                            class="vehicle-row hover:bg-gray-50 transition"
                            data-search="{{ strtolower(
                                $vehicle->nopol . ' ' .
                                ($vehicle->client->nama_lengkap ?? '') . ' ' .
                                ($vehicle->nama_pemilik ?? '') . ' ' .
                                ($vehicle->merk ?? '') . ' ' .
                                ($vehicle->tipe ?? '') . ' ' .
                                ($vehicle->tahun_pembuatan ?? '')
                            ) }}"
                        >

                            {{-- No --}}
                            <td class="px-6 py-4 text-gray-500">
                                {{ $index + 1 }}
                            </td>


                            {{-- Nomor Polisi --}}
                            <td class="px-6 py-4">

                                <span
                                    class="
                                        inline-flex
                                        items-center
                                        px-2.5 py-1
                                        rounded-md
                                        bg-gray-100
                                        text-gray-800
                                        font-semibold
                                        uppercase
                                    "
                                >
                                    {{ $vehicle->nopol }}
                                </span>

                            </td>


                            {{-- Nama Klien --}}
                            <td class="px-6 py-4">

                                <span class="font-medium text-gray-800">
                                    {{ $vehicle->client->nama_lengkap ?? 'Data Tidak Ditemukan' }}
                                </span>

                            </td>


                            {{-- Nama Pemilik --}}
                            <td class="px-6 py-4 text-gray-600">

                                {{ $vehicle->nama_pemilik ?? 'Data Tidak Ditemukan' }}

                            </td>


                            {{-- Merk & Tipe --}}
                            <td class="px-6 py-4">

                                <div class="text-gray-800 font-medium">
                                    {{ $vehicle->merk ?? '-' }}
                                </div>

                                <div class="text-xs text-gray-500 mt-0.5">
                                    {{ $vehicle->tipe ?? '-' }}
                                </div>

                            </td>


                            {{-- Tahun --}}
                            <td class="px-6 py-4 text-gray-600">

                                {{ $vehicle->tahun_pembuatan ?? '-' }}

                            </td>


                            {{-- Aksi --}}
                            <td class="px-6 py-4">

                                <div class="flex items-center justify-center gap-2">

                                    {{-- Arsip Dokumen --}}
                                    <a
                                        href="{{ route('documents.index', $vehicle->id) }}"
                                        class="
                                            px-3 py-1.5
                                            text-xs
                                            font-medium
                                            text-green-600
                                            bg-green-50
                                            hover:bg-green-100
                                            rounded-lg
                                            transition
                                        "
                                    >
                                        Dokumen
                                    </a>


                                    {{-- Edit --}}
                                    <a
                                        href="{{ route('vehicles.edit', $vehicle->id) }}"
                                        class="
                                            px-3 py-1.5
                                            text-xs
                                            font-medium
                                            text-indigo-600
                                            bg-indigo-50
                                            hover:bg-indigo-100
                                            rounded-lg
                                            transition
                                        "
                                    >
                                        Edit
                                    </a>


                                    {{-- Hapus --}}
                                    <form
                                        action="{{ route('vehicles.destroy', $vehicle->id) }}"
                                        method="POST"
                                        onsubmit="return confirm('Apakah Anda yakin ingin menghapus data kendaraan ini?');"
                                    >

                                        @csrf

                                        @method('DELETE')

                                        <button
                                            type="submit"
                                            class="
                                                px-3 py-1.5
                                                text-xs
                                                font-medium
                                                text-red-600
                                                bg-red-50
                                                hover:bg-red-100
                                                rounded-lg
                                                transition
                                            "
                                        >
                                            Hapus
                                        </button>

                                    </form>

                                </div>

                            </td>

                        </tr>

                    @empty

                        <tr>

                            <td
                                colspan="7"
                                class="px-6 py-12 text-center text-gray-500"
                            >

                                <div class="flex flex-col items-center">

                                    <svg
                                        class="w-12 h-12 text-gray-300 mb-3"
                                        fill="none"
                                        stroke="currentColor"
                                        viewBox="0 0 24 24"
                                    >

                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="1.5"
                                            d="M3 13h2l1-5h12l1 5h2M5 13v5m14-5v5M7 18h10M7 8l1-3h8l1 3"
                                        />

                                    </svg>

                                    <p class="font-medium text-gray-500">
                                        Belum ada data kendaraan.
                                    </p>

                                    <p class="text-sm text-gray-400 mt-1">
                                        Silakan tambahkan kendaraan baru.
                                    </p>

                                </div>

                            </td>

                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>


        {{-- ================================================= --}}
        {{-- PAGINATION --}}
        {{-- ================================================= --}}

        <div
            id="paginationContainer"
            class="
                flex
                flex-col
                sm:flex-row
                items-center
                justify-between
                gap-4
                px-6 py-4
                border-t
                border-gray-200
            "
        >

            {{-- Informasi --}}
            <p
                id="paginationInfo"
                class="text-sm text-gray-500"
            ></p>


            {{-- Tombol --}}
            <div
                id="paginationButtons"
                class="flex items-center gap-1"
            ></div>

        </div>

    </div>

</div>


{{-- ================================================= --}}
{{-- SEARCH + PAGINATION --}}
{{-- ================================================= --}}

<script>

document.addEventListener('DOMContentLoaded', function () {

    const rows = Array.from(
        document.querySelectorAll('.vehicle-row')
    );

    const searchInput =
        document.getElementById('vehicleSearch');

    const paginationInfo =
        document.getElementById('paginationInfo');

    const paginationButtons =
        document.getElementById('paginationButtons');

    const rowsPerPage = 10;

    let currentPage = 1;

    let filteredRows = [...rows];


    // =================================================
    // DISPLAY DATA
    // =================================================

    function displayRows() {

        const start =
            (currentPage - 1) * rowsPerPage;

        const end =
            start + rowsPerPage;


        // Sembunyikan semua row
        rows.forEach(function (row) {

            row.classList.add('hidden');

        });


        // Tampilkan row sesuai halaman
        filteredRows
            .slice(start, end)
            .forEach(function (row) {

                row.classList.remove('hidden');

            });


        updateNumber();

        updatePagination();

    }


    // =================================================
    // UPDATE NOMOR
    // =================================================

    function updateNumber() {

        const start =
            (currentPage - 1) * rowsPerPage;


        filteredRows
            .slice(start, start + rowsPerPage)
            .forEach(function (row, index) {

                const numberCell =
                    row.querySelector('td:first-child');

                numberCell.textContent =
                    start + index + 1;

            });

    }


    // =================================================
    // PAGINATION
    // =================================================

    function updatePagination() {

        paginationButtons.innerHTML = '';


        const totalRows =
            filteredRows.length;


        const totalPages =
            Math.ceil(totalRows / rowsPerPage);


        // Tidak ada data
        if (totalRows === 0) {

            paginationInfo.textContent =
                'Tidak ada data yang ditemukan';

            return;

        }


        const start =
            (currentPage - 1) * rowsPerPage + 1;


        const end =
            Math.min(
                currentPage * rowsPerPage,
                totalRows
            );


        paginationInfo.innerHTML = `
            Menampilkan
            <span class="font-medium text-gray-700">
                ${start}
            </span>
            sampai
            <span class="font-medium text-gray-700">
                ${end}
            </span>
            dari
            <span class="font-medium text-gray-700">
                ${totalRows}
            </span>
            data
        `;


        // =================================================
        // PREVIOUS
        // =================================================

        const previousButton =
            document.createElement('button');

        previousButton.innerHTML = '‹';

        previousButton.className = `
            px-3 py-2
            text-sm
            rounded-lg
            border
            ${
                currentPage === 1
                ? 'text-gray-400 bg-gray-100 border-gray-200 cursor-not-allowed'
                : 'text-gray-600 bg-white border-gray-200 hover:bg-gray-50'
            }
        `;

        previousButton.disabled =
            currentPage === 1;


        previousButton.addEventListener(
            'click',
            function () {

                if (currentPage > 1) {

                    currentPage--;

                    displayRows();

                }

            }
        );


        paginationButtons.appendChild(
            previousButton
        );


        // PAGE NUMBER

        for (
            let page = 1;
            page <= totalPages;
            page++
        ) {

            const button =
                document.createElement('button');

            button.textContent = page;


            if (page === currentPage) {

                button.className = `
                    px-3 py-2
                    text-sm
                    font-medium
                    text-white
                    bg-indigo-600
                    border
                    border-indigo-600
                    rounded-lg
                `;

            } else {

                button.className = `
                    px-3 py-2
                    text-sm
                    text-gray-600
                    bg-white
                    border
                    border-gray-200
                    rounded-lg
                    hover:bg-gray-50
                `;

            }


            button.addEventListener(
                'click',
                function () {

                    currentPage = page;

                    displayRows();

                }
            );


            paginationButtons.appendChild(
                button
            );

        }


        // =================================================
        // NEXT
        // =================================================

        const nextButton =
            document.createElement('button');

        nextButton.innerHTML = '›';

        nextButton.className = `
            px-3 py-2
            text-sm
            rounded-lg
            border
            ${
                currentPage === totalPages
                ? 'text-gray-400 bg-gray-100 border-gray-200 cursor-not-allowed'
                : 'text-gray-600 bg-white border-gray-200 hover:bg-gray-50'
            }
        `;

        nextButton.disabled =
            currentPage === totalPages;


        nextButton.addEventListener(
            'click',
            function () {

                if (currentPage < totalPages) {

                    currentPage++;

                    displayRows();

                }

            }
        );


        paginationButtons.appendChild(
            nextButton
        );

    }


    // =================================================
    // SEARCH
    // =================================================

    searchInput.addEventListener(
        'input',
        function () {

            const keyword =
                this.value
                    .toLowerCase()
                    .trim();


            filteredRows =
                rows.filter(function (row) {

                    const searchData =
                        row.dataset.search || '';

                    return searchData.includes(keyword);

                });


            currentPage = 1;

            displayRows();

        }
    );


    // =================================================
    // INITIAL LOAD
    // =================================================

    displayRows();

});

</script>

@endsection