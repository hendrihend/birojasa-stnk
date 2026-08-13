@extends('layouts.app')
@section('header_title', 'Daftar Klien')
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
                Data Klien
            </h2>

            <p class="text-sm text-gray-500 mt-1">
                Kelola data klien biro jasa STNK
            </p>
        </div>


        {{-- Tambah Klien --}}
        <a
            href="{{ route('clients.create') }}"
            class="
                inline-flex items-center justify-center gap-2
                px-4 py-2.5
                bg-indigo-600
                hover:bg-indigo-700
                text-white
                text-sm font-medium
                rounded-lg
                transition
            "
        >
            <span class="text-lg leading-none">+</span>
            Tambah Klien
        </a>

    </div>


    {{-- ================================================= --}}
    {{-- TABLE CARD --}}
    {{-- ================================================= --}}

    <div class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden">


        {{-- ================================================= --}}
        {{-- FILTER --}}
        {{-- ================================================= --}}

        <div
            class="
                p-5
                border-b border-gray-200
                flex flex-col lg:flex-row
                lg:items-center
                lg:justify-between
                gap-4
            "
        >

            {{-- Search --}}
            <div class="relative w-full lg:w-96">

                <div
                    class="
                        absolute inset-y-0 left-0
                        flex items-center
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
                    id="clientSearch"
                    placeholder="Cari nama, NIK, atau WhatsApp..."
                    class="
                        w-full
                        pl-10 pr-4 py-2.5
                        text-sm
                        border border-gray-300
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
                            Nama Lengkap
                        </th>

                        <th class="px-6 py-4 font-semibold text-gray-600">
                            NIK

                        <th class="px-6 py-4 font-semibold text-gray-600">
                            No WhatsApp
                        </th>

                        <th class="px-6 py-4 font-semibold text-gray-600">
                            Alamat
                        </th>

                        <th class="px-6 py-4 text-center font-semibold text-gray-600">
                            Aksi
                        </th>

                    </tr>

                </thead>


                <tbody
                    id="clientTableBody"
                    class="divide-y divide-gray-100"
                >

                    @forelse($clients as $index => $client)

                        <tr
                            class="client-row hover:bg-gray-50 transition"
                            data-search="{{ strtolower(
                                $client->nama_lengkap . ' ' .
                                ($client->nik ?? '') . ' ' .
                                ($client->no_whatsapp ?? '')
                            ) }}"
                        >

                            {{-- No --}}
                            <td class="px-6 py-4 text-gray-500">
                                {{ $index + 1 }}
                            </td>


                            {{-- Nama --}}
                            <td class="px-6 py-4">

                                <div class="font-semibold text-gray-800 uppercase">
                                    {{ $client->nama_lengkap }}
                                </div>

                            </td>


                            {{-- NIK --}}
                            <td class="px-6 py-4 text-gray-600">
                                {{ $client->nik ?? '-' }}
                            </td>


                            {{-- WhatsApp --}}
                            <td class="px-6 py-4 text-gray-600">
                                {{ $client->no_whatsapp ?? '-' }}
                            </td>


                            {{-- Alamat --}}
                            <td class="px-6 py-4 text-gray-600 max-w-xs">

                                <div class="truncate">
                                    {{ $client->alamat ?? '-' }}
                                </div>

                            </td>


                            {{-- Aksi --}}
                            <td class="px-6 py-4">

                                <div class="flex items-center justify-center gap-2">

                                    {{-- Edit --}}
                                    <a
                                        href="{{ route('clients.edit', $client->id) }}"
                                        class="
                                            px-3 py-1.5
                                            text-xs font-medium
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
                                        action="{{ route('clients.destroy', $client->id) }}"
                                        method="POST"
                                        onsubmit="return confirm('Apakah Anda yakin ingin menghapus data klien ini?');"
                                    >

                                        @csrf
                                        @method('DELETE')

                                        <button
                                            type="submit"
                                            class="
                                                px-3 py-1.5
                                                text-xs font-medium
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
                                Belum ada data klien.
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
                flex flex-col sm:flex-row
                items-center
                justify-between
                gap-4
                px-6 py-4
                border-t border-gray-200
            "
        >

            {{-- Informasi pagination --}}
            <p
                id="paginationInfo"
                class="text-sm text-gray-500"
            ></p>


            {{-- Tombol pagination --}}
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

    // Ambil semua baris data
    const rows = Array.from(
        document.querySelectorAll('.client-row')
    );

    // Search input
    const searchInput =
        document.getElementById('clientSearch');

    // Pagination info
    const paginationInfo =
        document.getElementById('paginationInfo');

    // Container tombol pagination
    const paginationButtons =
        document.getElementById('paginationButtons');


    // Jumlah data per halaman
    const rowsPerPage = 10;

    // Halaman saat ini
    let currentPage = 1;

    // Semua data awal
    let filteredRows = [...rows];


    // =================================================
    // MENAMPILKAN DATA
    // =================================================

    function displayRows() {

        const start =
            (currentPage - 1) * rowsPerPage;

        const end =
            start + rowsPerPage;


        // Sembunyikan semua data
        rows.forEach(function (row) {

            row.classList.add('hidden');

        });


        // Tampilkan data sesuai halaman
        filteredRows
            .slice(start, end)
            .forEach(function (row) {

                row.classList.remove('hidden');

            });


        // Update nomor
        updateNumber();

        // Update pagination
        updatePagination();

    }


    // =================================================
    // UPDATE NOMOR DATA
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
    // UPDATE PAGINATION
    // =================================================

    function updatePagination() {

        // Bersihkan tombol sebelumnya
        paginationButtons.innerHTML = '';


        // Jumlah data setelah pencarian
        const totalRows =
            filteredRows.length;


        // Hitung jumlah halaman
        const totalPages =
            Math.ceil(totalRows / rowsPerPage);


        // =================================================
        // TIDAK ADA DATA
        // =================================================

        if (totalRows === 0) {

            paginationInfo.textContent =
                'Tidak ada data yang ditemukan';

            return;

        }


        // =================================================
        // INFORMASI DATA
        // =================================================

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


        // =================================================
        // NOMOR HALAMAN
        // =================================================

        for (
            let page = 1;
            page <= totalPages;
            page++
        ) {

            const button =
                document.createElement('button');

            button.textContent = page;


            // Halaman aktif
            if (page === currentPage) {

                button.className = `
                    px-3 py-2
                    text-sm font-medium
                    text-white
                    bg-indigo-600
                    border border-indigo-600
                    rounded-lg
                `;

            }

            // Halaman tidak aktif
            else {

                button.className = `
                    px-3 py-2
                    text-sm
                    text-gray-600
                    bg-white
                    border border-gray-200
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


            // Filter data
            filteredRows =
                rows.filter(function (row) {

                    const searchData =
                        row.dataset.search || '';

                    return searchData.includes(keyword);

                });


            // Setelah search, kembali ke halaman pertama
            currentPage = 1;


            // Refresh tampilan
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