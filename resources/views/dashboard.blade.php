@extends('layouts.app')

@section('title', 'Dashboard')
@section('header_title', 'Dashboard')

@section('content')

{{-- QUICK SEARCH --}}
<div class="mb-6">
    <form action="{{ route('dashboard') }}" method="GET" class="relative group">
        <div class="absolute inset-y-0 left-0 flex items-center pl-5 pointer-events-none">
            <i class="fa-solid fa-magnifying-glass text-gray-400 group-focus-within:text-blue-500 transition-colors"></i>
        </div>

        <input type="text" name="search" value="{{ request('search') }}" placeholder="Pencarian cepat: Ketik Nomor Polisi atau Nama Klien di sini..." class="w-full pl-14 pr-28 py-3.5 bg-white border border-gray-200 rounded-xl shadow-sm text-sm text-gray-700 placeholder-gray-400 outline-none focus:ring-2 focus:ring-blue-100 focus:border-blue-400 transition">

        <div class="absolute inset-y-2 right-2 flex items-center gap-1">
            @if(request('search'))
                <a
                    href="{{ route('dashboard') }}"
                    class="w-9 h-9 flex items-center justify-center rounded-lg text-gray-400 hover:text-red-500 hover:bg-red-50 transition">
                    <i class="fa-solid fa-xmark"></i>
                </a>
            @endif

            <button type="submit" class="h-9 px-5 bg-indigo-700 hover:bg-indigo-800 text-white text-sm font-medium rounded-lg transition duration-200">
                Cari
            </button>
        </div>
    </form>
</div>


{{-- HASIL PENCARIAN --}}
@if(request('search'))
<div class="mb-6 bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">

    <div class="px-5 py-4 border-b border-gray-100 bg-gray-50 flex justify-between items-center">
        <h2 class="text-sm font-semibold text-gray-800">
            <i class="fa-solid fa-list-check text-blue-500 mr-2"></i>
            Hasil Pencarian: "{{ request('search') }}"
        </h2>

        <span class="text-xs font-medium bg-blue-50 text-blue-600 px-3 py-1 rounded-full">
            {{ $searchResults->count() }} Ditemukan
        </span>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-left">
            <tbody class="text-sm divide-y divide-gray-100">

                @forelse($searchResults as $vehicle)

                <tr class="hover:bg-gray-50 transition">
                    <td class="px-5 py-4 font-semibold text-gray-800">
                        {{ $vehicle->nopol }}
                    </td>

                    <td class="px-5 py-4 text-gray-600">
                        {{ $vehicle->merk }} {{ $vehicle->tipe }}
                    </td>

                    <td class="px-5 py-4">
                        <span class="inline-flex items-center gap-1.5 text-xs text-blue-700 bg-blue-50 border border-blue-100 px-3 py-1.5 rounded-full">
                            <i class="fa-solid fa-user"></i>
                            {{ $vehicle->client->nama_lengkap ?? 'Tanpa Pemilik' }}
                        </span>
                    </td>

                    <td class="px-5 py-4">
                        <div class="flex justify-end gap-2">

                            <a
                                href="{{ route('transactions.create', ['vehicle_id' => $vehicle->id]) }}"
                                class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-gray-900 hover:bg-gray-800 text-white text-xs font-medium rounded-lg transition"
                            >
                                <i class="fa-solid fa-plus"></i>
                                Transaksi
                            </a>

                            <a
                                href="{{ route('vehicles.show', $vehicle->id) }}"
                                class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-blue-50 hover:bg-blue-100 text-blue-600 text-xs font-medium rounded-lg transition"
                            >
                                <i class="fa-solid fa-eye"></i>
                                Detail
                            </a>

                        </div>
                    </td>
                </tr>

                @empty

                <tr>
                    <td colspan="4" class="px-5 py-10 text-center text-gray-500">
                        <i class="fa-solid fa-magnifying-glass-minus text-3xl mb-3 text-gray-300"></i>
                        <p class="text-sm">
                            Tidak ada kendaraan atau klien yang cocok dengan
                            "<strong>{{ request('search') }}</strong>".
                        </p>
                    </td>
                </tr>

                @endforelse

            </tbody>
        </table>
    </div>
</div>
@endif


{{-- SUMMARY CARDS --}}
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-5 gap-4 mb-7">

    {{-- Total Kendaraan --}}
    <div class="bg-white border border-gray-200 rounded-xl p-5 shadow-sm hover:shadow-md transition">
        <div class="flex items-center gap-4">

            <div class="w-11 h-11 rounded-full bg-blue-50 flex items-center justify-center text-blue-500 shrink-0">
                <i class="fa-solid fa-car text-lg"></i>
            </div>

            <div class="min-w-0">
                <p class="text-sm text-gray-500 mb-1">
                    Total Kendaraan
                </p>

                <h3 class="text-2xl font-semibold text-gray-800">
                    {{ $totalKendaraan ?? 0 }}
                </h3>
            </div>

        </div>
    </div>


    {{-- STNK Aktif --}}
    <div class="bg-white border border-gray-200 rounded-xl p-5 shadow-sm hover:shadow-md transition">
        <div class="flex items-center gap-4">

            <div class="w-11 h-11 rounded-full bg-blue-50 flex items-center justify-center text-blue-500 shrink-0">
                <i class="fa-solid fa-file-circle-check text-lg"></i>
            </div>

            <div class="min-w-0">
                <p class="text-sm text-gray-500 mb-1">
                    STNK Aktif
                </p>

                <h3 class="text-2xl font-semibold text-gray-800">
                    {{ $stnkAktif ?? 0 }}
                </h3>
            </div>

        </div>
    </div>


    {{-- Jatuh Tempo --}}
    <div class="bg-white border border-gray-200 rounded-xl p-5 shadow-sm hover:shadow-md transition">
        <div class="flex items-center gap-4">

            <div class="w-11 h-11 rounded-full bg-orange-50 flex items-center justify-center text-orange-500 shrink-0">
                <i class="fa-regular fa-calendar-xmark text-lg"></i>
            </div>

            <div class="min-w-0">
                <p class="text-sm text-gray-500 mb-1">
                    Jatuh Tempo (90 Hari)
                </p>

                <h3 class="text-2xl font-semibold text-orange-500">
                    {{ count($alerts ?? []) }}
                </h3>
            </div>

        </div>
    </div>


    {{-- Proses Pengurusan --}}
    <div class="bg-white border border-gray-200 rounded-xl p-5 shadow-sm hover:shadow-md transition">
        <div class="flex items-center gap-4">

            <div class="w-11 h-11 rounded-full bg-blue-50 flex items-center justify-center text-blue-500 shrink-0">
                <i class="fa-solid fa-hourglass-half text-lg"></i>
            </div>

            <div class="min-w-0">
                <p class="text-sm text-gray-500 mb-1">
                    Proses Pengurusan
                </p>

                <h3 class="text-2xl font-semibold text-blue-600">
                    {{ $kendaraanDiproses ?? 0 }}
                </h3>
            </div>

        </div>
    </div>


    {{-- Total Pengeluaran --}}
    <div class="bg-white border border-gray-200 rounded-xl p-5 shadow-sm hover:shadow-md transition">
        <div class="flex items-center gap-4">

            <div class="w-11 h-11 rounded-full bg-blue-50 flex items-center justify-center text-blue-500 shrink-0">
                <i class="fa-solid fa-wallet text-lg"></i>
            </div>

            <div class="min-w-0">
                <p class="text-sm text-gray-500 mb-1">
                    Total Pengeluaran
                </p>

                <h3 class="text-xl font-semibold text-gray-800 whitespace-nowrap">
                    Rp {{ number_format($totalPengeluaran ?? 0, 0, ',', '.') }}
                </h3>
            </div>

        </div>
    </div>

</div>


{{-- INFORMASI TAMBAHAN --}}
<div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-7">

    {{-- Selesai Diproses --}}
    <div class="bg-white border border-gray-200 rounded-xl p-5 shadow-sm">
        <div class="flex items-center justify-between">

            <div class="flex items-center gap-4">
                <div class="w-11 h-11 rounded-full bg-green-50 flex items-center justify-center text-green-500">
                    <i class="fa-solid fa-circle-check text-lg"></i>
                </div>

                <div>
                    <p class="text-sm text-gray-500 mb-1">
                        Kendaraan Selesai Diproses
                    </p>

                    <h3 class="text-2xl font-semibold text-gray-800">
                        {{ $kendaraanSelesaiDiproses ?? 0 }}
                    </h3>
                </div>
            </div>

        </div>
    </div>


    {{-- Cancel --}}
    <div class="bg-white border border-gray-200 rounded-xl p-5 shadow-sm">
        <div class="flex items-center gap-4">

            <div class="w-11 h-11 rounded-full bg-red-50 flex items-center justify-center text-red-500">
                <i class="fa-solid fa-circle-xmark text-lg"></i>
            </div>

            <div>
                <p class="text-sm text-gray-500 mb-1">
                    Kendaraan Cancel
                </p>

                <h3 class="text-2xl font-semibold text-gray-800">
                    {{ $kendaraanCancel ?? 0 }}
                </h3>
            </div>

        </div>
    </div>

</div>


{{-- PERINGATAN JATUH TEMPO --}}
<div class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden">

    {{-- Header --}}
    <div class="px-5 py-4 border-b border-gray-100 flex justify-between items-center">

        <div class="flex items-center gap-3">
            <i class="fa-regular fa-bell text-red-500"></i>

            <h2 class="text-base font-semibold text-gray-800">
                Peringatan Jatuh Tempo Pajak
            </h2>
        </div>

        <span class="bg-red-50 text-red-600 text-xs font-medium px-3 py-1 rounded-full">
            {{ count($alerts ?? []) }} Peringatan
        </span>

    </div>


    {{-- Alert List --}}
    <div class="p-5">

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">

            @forelse($alerts as $alert)

                @php
                    $bgClass = 'bg-gray-50 border-gray-200';
                    $textClass = 'text-gray-800';
                    $badgeClass = 'bg-gray-100 text-gray-700';
                    $btnClass = 'bg-gray-800 hover:bg-gray-900';
                    $btnText = 'Urus Sekarang';

                    if ($alert->kategori_warna == 'danger' || $alert->kategori_warna == 'merah') {
                        $bgClass = 'bg-red-50 border-red-200';
                        $textClass = 'text-red-700';
                        $badgeClass = 'bg-red-100 text-red-700';
                        $btnClass = 'bg-red-500 hover:bg-red-600';
                    } elseif ($alert->kategori_warna == 'warning' || $alert->kategori_warna == 'kuning') {
                        $bgClass = 'bg-yellow-50 border-yellow-200';
                        $textClass = 'text-yellow-800';
                        $badgeClass = 'bg-yellow-100 text-yellow-800';
                        $btnClass = 'bg-yellow-500 hover:bg-yellow-600';
                    } elseif ($alert->kategori_warna == 'info') {
                        $bgClass = 'bg-blue-50 border-blue-200';
                        $textClass = 'text-blue-700';
                        $badgeClass = 'bg-blue-100 text-blue-700';
                        $btnClass = 'bg-blue-600 hover:bg-blue-700';
                        $btnText = 'Cek Transaksi';
                    }
                @endphp


                <div class="border rounded-xl p-4 {{ $bgClass }} transition hover:shadow-sm">

                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">

                        {{-- Informasi --}}
                        <div class="min-w-0">

                            <div class="flex items-center gap-2 mb-1.5">

                                <span class="text-lg font-semibold tracking-wide {{ $textClass }}">
                                    {{ $alert->nopol }}
                                </span>

                                <span class="text-xs font-medium px-2 py-1 rounded-md {{ $badgeClass }}">
                                    {{ $alert->tipe }}
                                </span>

                            </div>

                            <p class="text-sm {{ $textClass }}">
                                {{ $alert->layanan }}
                                <span class="mx-1">•</span>

                                <span class="font-medium">
                                    {{ $alert->status_teks }}
                                </span>

                                <span class="ml-1">
                                    ({{ \Carbon\Carbon::parse($alert->tanggal_asli)->format('d M Y') }})
                                </span>
                            </p>

                        </div>


                        {{-- Button --}}
                        <a
                            href="{{ route('transactions.index', ['search' => $alert->nopol]) }}"
                            class="shrink-0 inline-flex items-center justify-center gap-2 px-4 py-2.5 {{ $btnClass }} text-white text-sm font-medium rounded-lg transition"
                        >
                            <i class="fa-solid fa-bolt text-xs"></i>
                            {{ $btnText }}
                        </a>

                    </div>

                </div>

            @empty

                <div class="col-span-full py-12 text-center">

                    <div class="text-4xl mb-3 text-green-500">
                        <i class="fa-solid fa-circle-check"></i>
                    </div>

                    <h3 class="text-base font-medium text-gray-800 mb-1">
                        Semua Kendaraan Aman
                    </h3>

                    <p class="text-sm text-gray-500">
                        Tidak ada STNK yang mendekati jatuh tempo dalam waktu dekat.
                    </p>

                </div>

            @endforelse

        </div>

    </div>

</div>

@endsection