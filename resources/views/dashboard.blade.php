<!-- Master Layout -->
@extends('layouts.app')

@section('title', 'Dashboard')
@section('header_title', 'Dashboard')

@section('content')

<div class="space-y-6">

    {{-- ===================================================== --}}
    {{-- SUMMARY CARDS --}}
    {{-- ===================================================== --}}

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4">

        {{-- Total Kendaraan --}}
        <div class="bg-white border border-gray-200 rounded-lg p-5 shadow-sm">
            <p class="text-sm text-gray-500">
                Total Kendaraan
            </p>

            <h3 class="mt-2 text-2xl font-bold text-gray-800">
                {{ number_format($totalKendaraan, 0, ',', '.') }}
            </h3>
        </div>


        {{-- STNK Aktif --}}
        <div class="bg-white border border-gray-200 rounded-lg p-5 shadow-sm">
            <p class="text-sm text-gray-500">
                STNK Aktif
            </p>

            <h3 class="mt-2 text-2xl font-bold text-gray-800">
                {{ number_format($stnkAktif, 0, ',', '.') }}
            </h3>
        </div>


        {{-- Jatuh Tempo --}}
        <div class="bg-white border border-gray-200 rounded-lg p-5 shadow-sm">
            <p class="text-sm text-gray-500">
                Jatuh Tempo (90 Hari)
            </p>

            <h3 class="mt-2 text-2xl font-bold text-orange-500">
                {{ $alerts->count() }}
            </h3>
        </div>


        {{-- Proses Pengurusan --}}
        <div class="bg-white border border-gray-200 rounded-lg p-5 shadow-sm">
            <p class="text-sm text-gray-500">
                Proses Pengurusan
            </p>

            <h3 class="mt-2 text-2xl font-bold text-blue-600">
                {{ $kendaraanDiproses }}
            </h3>
        </div>


        {{-- Total Pengeluaran --}}
        <div class="bg-white border border-gray-200 rounded-lg p-5 shadow-sm">
            <p class="text-sm text-gray-500">
                Total Pengeluaran
            </p>

            <h3 class="mt-2 text-xl font-bold text-gray-800">
                Rp {{ number_format($totalPengeluaran, 0, ',', '.') }}
            </h3>
        </div>

    </div>

    {{-- GRAFIK --}}

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

    {{--LINE CHART --}}

        <div class="lg:col-span-2 bg-white border border-gray-200 rounded-lg p-6 shadow-sm">

            <div class="mb-5">
                <h2 class="text-lg font-semibold text-gray-800">
                    Pengurusan STNK
                </h2>

                <p class="text-sm text-gray-500">
                    Jumlah pengurusan STNK per bulan
                </p>
            </div>

            <div class="relative h-[300px]">
                <canvas id="stnkLineChart"></canvas>
            </div>

        </div>


        {{-- PIE CHART --}}
        <div class="bg-white border border-gray-200 rounded-lg p-6 shadow-sm">

            <div class="mb-5">
                <h2 class="text-lg font-semibold text-gray-800">
                    Status Pengurusan
                </h2>

                <p class="text-sm text-gray-500">
                    Distribusi status kendaraan
                </p>
            </div>

            <div class="relative h-[300px]">
                <canvas id="statusChart"></canvas>
            </div>

        </div>

    </div>

    {{-- TABLE JATUH TEMPO --}}

    <section>

        <div class="bg-white border border-gray-200 rounded-lg shadow-sm overflow-hidden">

    <div class="overflow-x-auto">

        <table class="w-full text-sm text-left">

            <thead class="bg-gray-50 border-b border-gray-200">
                <tr>
                    <th class="px-6 py-3 font-semibold text-gray-600">No</th>
                    <th class="px-6 py-3 font-semibold text-gray-600">No. Polisi</th>
                    <th class="px-6 py-3 font-semibold text-gray-600">Tipe Kendaraan</th>
                    <th class="px-6 py-3 font-semibold text-gray-600">Layanan</th>
                    <th class="px-6 py-3 font-semibold text-gray-600">Jatuh Tempo</th>
                    <th class="px-6 py-3 font-semibold text-gray-600">Status</th>
                    <th class="px-6 py-3 text-center font-semibold text-gray-600">Aksi</th>
                </tr>
            </thead>

            <tbody id="alertTableBody" class="divide-y divide-gray-100">

                @forelse($alerts as $index => $alert)

                    <tr class="alert-row hover:bg-gray-50">

                        <td class="px-6 py-4 text-gray-500">
                            {{ $index + 1 }}
                        </td>

                        <td class="px-6 py-4 font-semibold text-gray-800">
                            {{ $alert->nopol }}
                        </td>

                        <td class="px-6 py-4 text-gray-600">
                            {{ $alert->tipe }}
                        </td>

                        <td class="px-6 py-4 text-gray-600">
                            {{ $alert->layanan }}
                        </td>

                        <td class="px-6 py-4 font-medium text-gray-800">
                            {{ $alert->tanggal_asli }}
                        </td>

                        <td class="px-6 py-4">

                            @if($alert->kategori_warna == 'danger')

                                <span class="px-3 py-1 rounded-full text-xs font-medium bg-red-100 text-red-700">
                                    {{ $alert->status_teks }}
                                </span>

                            @elseif($alert->kategori_warna == 'warning')

                                <span class="px-3 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-700">
                                    {{ $alert->status_teks }}
                                </span>

                            @else

                                <span class="px-3 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-700">
                                    {{ $alert->status_teks }}
                                </span>

                            @endif

                        </td>

                        <td class="px-6 py-4 text-center">

                            <a
                                href="{{ route('transactions.create', ['vehicle_id' => $alert->vehicle_id]) }}"
                                class="inline-block px-3 py-2 bg-red-500 text-white text-xs font-medium rounded-lg hover:bg-red-600 transition"
                            >
                                Urus Sekarang
                            </a>

                        </td>

                    </tr>

                @empty

                    <tr>
                        <td colspan="7" class="px-6 py-10 text-center text-gray-500">
                            Tidak ada STNK yang mendekati jatuh tempo.
                        </td>
                    </tr>

                @endforelse

            </tbody>

        </table>

            {{-- PAGINATION --}}
    <div
        id="paginationContainer"
        class="flex items-center justify-between px-6 py-4 border-t border-gray-200"
    >

        {{-- Informasi data --}}
        <p id="paginationInfo" class="text-sm text-gray-500"></p>

        {{-- Tombol --}}
        <div id="paginationButtons" class="flex items-center gap-1"></div>

    </div>

</div>

    </div>

    </section>

</div>


{{-- ===================================================== --}}
{{-- CHART.JS --}}
{{-- ===================================================== --}}

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>

    /* =====================================================
       LINE CHART
       ===================================================== */

    const lineChart = document.getElementById('stnkLineChart');

    new Chart(lineChart, {

        type: 'line',

        data: {

            labels: [
                'Jan',
                'Feb',
                'Mar',
                'Apr',
                'Mei',
                'Jun',
                'Jul',
                'Agu'
            ],

            datasets: [{

                label: 'Pengurusan STNK',

                data: [
                    12,
                    19,
                    15,
                    25,
                    22,
                    30,
                    27,
                    35
                ],

                borderWidth: 2,

                tension: 0.4,

                fill: false,

                pointRadius: 4

            }]

        },

        options: {

            responsive: true,

            maintainAspectRatio: false,

            plugins: {

                legend: {
                    display: false
                }

            },

            scales: {

                y: {

                    beginAtZero: true,

                    ticks: {
                        precision: 0
                    }

                }

            }

        }

    });


    /* =====================================================
       DOUGHNUT CHART
       ===================================================== */

    const statusChart = document.getElementById('statusChart');

    new Chart(statusChart, {

        type: 'doughnut',

        data: {

            labels: [
                'Selesai',
                'Diproses',
                'Menunggu'
            ],

            datasets: [{

                data: [
                    60,
                    25,
                    15
                ],

                borderWidth: 2

            }]

        },

        options: {

            responsive: true,

            maintainAspectRatio: false,

            cutout: '60%',

            plugins: {

                legend: {

                    position: 'bottom'

                }

            }

        }

    });

</script>

<script>
document.addEventListener('DOMContentLoaded', function () {

    const rows = document.querySelectorAll('.alert-row');

    const rowsPerPage = 10;

    let currentPage = 1;

    const totalRows = rows.length;

    const totalPages = Math.ceil(totalRows / rowsPerPage);

    const paginationInfo = document.getElementById('paginationInfo');

    const paginationButtons = document.getElementById('paginationButtons');


    function showPage(page) {

        currentPage = page;

        const start = (page - 1) * rowsPerPage;

        const end = start + rowsPerPage;


        // Sembunyikan semua row
        rows.forEach((row, index) => {

            if (index >= start && index < end) {

                row.classList.remove('hidden');

            } else {

                row.classList.add('hidden');

            }

        });


        // Informasi data
        const firstItem = totalRows === 0 ? 0 : start + 1;

        const lastItem = Math.min(end, totalRows);

        paginationInfo.innerHTML = `
            Menampilkan
            <span class="font-medium text-gray-700">${firstItem}</span>
            sampai
            <span class="font-medium text-gray-700">${lastItem}</span>
            dari
            <span class="font-medium text-gray-700">${totalRows}</span>
            data
        `;


        renderPagination();

    }


    function renderPagination() {

        paginationButtons.innerHTML = '';


        // Previous
        const previousButton = document.createElement('button');

        previousButton.innerHTML = '‹';

        previousButton.className = `
            px-3 py-2 text-sm rounded-lg border
            ${currentPage === 1
                ? 'text-gray-400 bg-gray-100 border-gray-200 cursor-not-allowed'
                : 'text-gray-600 bg-white border-gray-200 hover:bg-gray-50'}
        `;

        previousButton.disabled = currentPage === 1;

        previousButton.addEventListener('click', function () {

            if (currentPage > 1) {

                showPage(currentPage - 1);

            }

        });

        paginationButtons.appendChild(previousButton);


        // Nomor halaman
        for (let page = 1; page <= totalPages; page++) {

            const pageButton = document.createElement('button');

            pageButton.textContent = page;

            if (page === currentPage) {

                pageButton.className = `
                    px-3 py-2 text-sm font-medium
                    text-white bg-indigo-600
                    border border-indigo-600
                    rounded-lg
                `;

            } else {

                pageButton.className = `
                    px-3 py-2 text-sm
                    text-gray-600 bg-white
                    border border-gray-200
                    rounded-lg hover:bg-gray-50
                `;

            }


            pageButton.addEventListener('click', function () {

                showPage(page);

            });


            paginationButtons.appendChild(pageButton);

        }


        // Next
        const nextButton = document.createElement('button');

        nextButton.innerHTML = '›';

        nextButton.className = `
            px-3 py-2 text-sm rounded-lg border
            ${currentPage === totalPages
                ? 'text-gray-400 bg-gray-100 border-gray-200 cursor-not-allowed'
                : 'text-gray-600 bg-white border-gray-200 hover:bg-gray-50'}
        `;

        nextButton.disabled = currentPage === totalPages;

        nextButton.addEventListener('click', function () {

            if (currentPage < totalPages) {

                showPage(currentPage + 1);

            }

        });

        paginationButtons.appendChild(nextButton);

    }


    // Jalankan halaman pertama
    if (totalRows > 0) {

        showPage(1);

    } else {

        paginationInfo.textContent = 'Tidak ada data';

    }

});
</script>

@endsection

   