<!-- Master Layout -->
@extends('layouts.app')
@section('title', 'Dashboard')
@section('header_title', 'Dashboard')

  <!-- Main Content -->
  @section('content')

  <!-- Kolom Pencarian Cepat (Quick Search) -->
    <div class="mb-6 bg-gradient-to-r from-blue-50/60 via-white to-white rounded-2xl shadow-sm border border-gray-300 p-2 pl-3 flex items-center gap-2 hover:shadow-md transition-shadow">
        <div class="w-11 h-11 rounded-xl bg-blue-100 text-blue-600 flex items-center justify-center text-lg shrink-0">
            <i class="fa-solid fa-magnifying-glass"></i>
        </div>

        <!-- Ubah route-nya menjadi 'dashboard' -->
        <form action="{{ route('dashboard') }}" method="GET" class="flex-1 flex items-center gap-2">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Pencarian cepat: Ketik Nomor Polisi atau Nama Klien di sini..." 
                class="flex-1 min-w-0 px-3 py-2.5 bg-transparent border-0 text-gray-700 placeholder-gray-400 outline-none focus:ring-0 text-base">

            @if(request('search'))
                <!-- Tombol Reset X -->
                <a href="{{ route('dashboard') }}" class="px-3 py-2 text-gray-400 hover:text-red-500 hover:bg-red-50 rounded-lg transition-colors flex items-center shrink-0">
                    <i class="fa-solid fa-xmark text-lg"></i>
                </a>
            @endif
            <button type="submit" class="px-6 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-xl transition-colors flex items-center gap-2 shrink-0">
                <i class="fa-solid fa-magnifying-glass text-sm"></i>
                <span>Cari</span>
            </button>
        </form>
    </div>

    <!-- BLOK HASIL PENCARIAN (Hanya Muncul Jika Ada Kata Kunci) -->
    @if(request('search'))
        <div class="mb-8 bg-white rounded-xl shadow-sm border border-blue-200 overflow-hidden ring-1 ring-blue-100">
            <div class="p-4 border-b border-gray-300 bg-blue-50/50 flex justify-between items-center">
                <h2 class="font-semibold text-gray-800">
                    <i class="fa-solid fa-list-check text-blue-500 mr-2"></i> Hasil Pencarian: "{{ request('search') }}"
                </h2>
                <span class="text-xs font-semibold bg-blue-100 text-blue-700 px-3 py-1 rounded-full">{{ $searchResults->count() }} Ditemukan</span>
            </div>
            
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <tbody class="text-sm divide-y divide-gray-300">
                        @forelse($searchResults as $vehicle)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="p-4 font-semibold text-gray-800 text-base w-32">{{ $vehicle->nopol }}</td>
                            <td class="p-4 text-gray-700">{{ $vehicle->merk }} {{ $vehicle->tipe }}</td>
                            <td class="p-4 text-gray-600">
                                <span class="font-semibold text-blue-700 bg-blue-50 border border-blue-100 px-3 py-1 rounded-full text-xs">
                                    <i class="fa-solid fa-user"></i> {{ $vehicle->client->nama_lengkap ?? 'Tanpa Pemilik' }}
                                </span>
                            </td>
                            <td class="p-4 flex justify-end gap-3">
                                <a href="{{ route('transactions.create', ['vehicle_id' => $vehicle->id]) }}" class="text-xs px-3 py-1.5 bg-blue-600 text-white font-semibold rounded-lg hover:bg-blue-700 transition-colors">
                                    <i class="fa-solid fa-plus mr-1"></i> Transaksi
                                </a>
                                <a href="{{ route('vehicles.show', $vehicle->id) }}" class="text-xs px-3 py-1.5 bg-blue-50 text-blue-700 font-semibold rounded-lg hover:bg-blue-100 transition-colors">
                                    <i class="fa-solid fa-eye mr-1"></i> Detail
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="p-8 text-center text-gray-500">
                                <i class="fa-solid fa-magnifying-glass-minus text-3xl mb-3 opacity-30"></i>
                                <p>Tidak ada kendaraan atau klien yang cocok dengan "<strong>{{ request('search') }}</strong>".</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    @endif
    
    <!-- Bagian Top Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5 mb-8">
        <!-- Card 1: Kendaraan -->
        <div class="bg-gradient-to-br from-white to-blue-50/50 rounded-2xl shadow-sm p-5 border border-gray-300 border-l-4 border-l-blue-500 flex items-center gap-4 hover:shadow-md transition-shadow">
            <div class="w-12 h-12 rounded-xl bg-blue-100 flex items-center justify-center text-blue-600 text-xl shrink-0">
                <i class="fa-solid fa-car"></i>
            </div>
            <div>
                <p class="text-sm font-normal text-gray-500 mb-0.5">Total Kendaraan</p>
                <h3 class="text-xl font-semibold text-gray-800">{{ $totalKendaraan ?? 0 }}</h3>
            </div>
        </div>

        <!-- Card 2: STNK Aktif -->
        <div class="bg-gradient-to-br from-white to-indigo-50/50 rounded-2xl shadow-sm p-5 border border-gray-300 border-l-4 border-l-indigo-500 flex items-center gap-4 hover:shadow-md transition-shadow">
            <div class="w-12 h-12 rounded-xl bg-indigo-100 flex items-center justify-center text-indigo-600 text-xl shrink-0">
                <i class="fa-solid fa-file-circle-check"></i>
            </div>
            <div>
                <p class="text-sm font-normal text-gray-500 mb-0.5">STNK Aktif</p>
                <h3 class="text-xl font-semibold text-gray-800">{{ $stnkAktif ?? 0 }}</h3>
            </div>
        </div>

        <!-- Card 3: Total Pengeluaran -->
        <div class="bg-gradient-to-br from-white to-amber-50/50 rounded-2xl shadow-sm p-5 border border-gray-300 border-l-4 border-l-amber-500 flex items-center gap-4 hover:shadow-md transition-shadow">
            <div class="w-12 h-12 rounded-xl bg-amber-100 flex items-center justify-center text-amber-600 text-xl shrink-0">
                <i class="fa-solid fa-wallet"></i>
            </div>
            <div>
                <p class="text-sm font-normal text-gray-500 mb-0.5">Total Pengeluaran</p>
                <h3 class="text-lg font-semibold text-gray-800 number">Rp {{ number_format($totalPengeluaran ?? 0, 0, ',', '.') }}</h3>
            </div>
        </div>

        <!-- Card 4: Kendaraan Diproses -->
        <div class="bg-gradient-to-br from-white to-sky-50/50 rounded-2xl shadow-sm p-5 border border-gray-300 border-l-4 border-l-sky-500 flex items-center gap-4 hover:shadow-md transition-shadow">
            <div class="w-12 h-12 rounded-xl bg-sky-100 flex items-center justify-center text-sky-600 text-xl shrink-0">
                <i class="fa-solid fa-hourglass-half"></i>
            </div>
            <div>
                <p class="text-sm font-normal text-gray-500 mb-0.5">Kendaraan Diproses</p>
                <h3 class="text-xl font-semibold text-gray-800">{{ $kendaraanDiproses ?? 0 }}</h3>
            </div>
        </div>
        
        <!-- Card 5: Kendaraan Selesai Diproses -->
        <div class="bg-gradient-to-br from-white to-green-50/50 rounded-2xl shadow-sm p-5 border border-gray-300 border-l-4 border-l-green-500 flex items-center gap-4 hover:shadow-md transition-shadow">
            <div class="w-12 h-12 rounded-xl bg-green-100 flex items-center justify-center text-green-600 text-xl shrink-0">
                <i class="fa-solid fa-hourglass-end"></i>
            </div>
            <div>
                <p class="text-sm font-normal text-gray-500 mb-0.5">Kendaraan Selesai</p>
                <h3 class="text-xl font-semibold text-gray-800">{{ $kendaraanSelesaiDiproses ?? 0 }}</h3>
            </div>
        </div>

        <!-- Card 6: Kendaraan Cancel -->
        <div class="bg-gradient-to-br from-white to-red-50/50 rounded-2xl shadow-sm p-5 border border-gray-300 border-l-4 border-l-red-500 flex items-center gap-4 hover:shadow-md transition-shadow">
            <div class="w-12 h-12 rounded-xl bg-red-100 flex items-center justify-center text-red-600 text-xl shrink-0">
                <i class="fa-solid fa-circle-xmark"></i>
            </div>
            <div>
                <p class="text-sm font-normal text-gray-500 mb-0.5">Kendaraan Cancel</p>
                <h3 class="text-xl font-semibold text-gray-800">{{ $kendaraanCancel ?? 0 }}</h3>
            </div>
        </div>

    </div>

    <!-- Section Peringatan Jatuh Tempo (Fokus Utama Dashboard) -->
    <div class="bg-white rounded-2xl shadow-md border border-gray-300 overflow-hidden">
        
        <!-- Header Section -->
        <div class="p-6 border-b border-gray-300 flex justify-between items-center bg-gradient-to-r from-red-50/70 via-white to-white">
            <h2 class="text-xl font-semibold text-gray-800 flex items-center gap-3">
                <span class="w-10 h-10 rounded-xl bg-red-100 flex items-center justify-center text-red-600 text-base shadow-sm">
                    <i class="fa-solid fa-bell"></i>
                </span>
                Peringatan Jatuh Tempo Pajak
            </h2>
            <span class="bg-red-600 text-white text-xs font-semibold px-3.5 py-1.5 rounded-full shadow-sm">{{ count($alerts ?? []) }} Peringatan</span>
        </div>
        
        <!-- Isi Peringatan (Grid 2 Kolom) -->
        <div class="p-6">
            <div class="grid gird-flow-col grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                @forelse($alerts as $alert)
                    @php
                        // Logika untuk mengubah kategori warna dari Controller menjadi Class Tailwind
                        $accentClass = 'border-l-gray-400';
                        $iconBg = 'bg-gray-100 text-gray-500';
                        $icon = 'fa-circle-info';
                        $badgeClass = 'bg-gray-100 text-gray-700';
                        $statusClass = 'text-gray-700';
                        $btnClass = 'bg-gray-800 hover:bg-gray-900 text-white'; // Tombol default
                        $btnText = 'Urus Sekarang';
                        
                        if($alert->kategori_warna == 'danger' || $alert->kategori_warna == 'merah') {
                            $accentClass = 'border-l-red-500';
                            $iconBg = 'bg-red-50 text-red-600';
                            $icon = 'fa-triangle-exclamation';
                            $badgeClass = 'bg-red-50 text-red-700';
                            $statusClass = 'text-red-600';
                            $btnClass = 'bg-red-500 hover:bg-red-600 text-white';
                        } elseif($alert->kategori_warna == 'warning' || $alert->kategori_warna == 'kuning') {
                            $accentClass = 'border-l-orange-500';
                            $iconBg = 'bg-orange-50 text-orange-600';
                            $icon = 'fa-clock';
                            $badgeClass = 'bg-orange-50 text-orange-700';
                            $statusClass = 'text-orange-600';
                            $btnClass = 'bg-orange-500 hover:bg-orange-600 text-white';
                        } elseif($alert->kategori_warna == 'info') {
                            // Warna biru khusus untuk kendaraan "SEDANG DIURUS"
                             $accentClass = 'border-l-blue-500';
                             $iconBg = 'bg-blue-50 text-blue-600';
                             $icon = 'fa-hourglass-half';
                             $badgeClass = 'bg-blue-50 text-blue-700';
                             $statusClass = 'text-blue-600';
                             $btnClass = 'bg-blue-600 hover:bg-blue-700 text-white';
                             $btnText = 'Cek Transaksi'; // ubah teks tombolnya
                        }
                    @endphp

                    <!-- Card Peringatan Individual -->
                    <div class="rounded-xl border border-gray-300 border-l-4 {{ $accentClass }} bg-white p-5 flex flex-col gap-4 transition-shadow hover:shadow-md">
                        
                        <!-- Info Utama -->
                        <div class="flex items-start gap-3">
                            <span class="w-9 h-9 rounded-lg {{ $iconBg }} flex items-center justify-center text-sm shrink-0">
                                <i class="fa-solid {{ $icon }}"></i>
                            </span>
                            <div class="flex-1 min-w-0 space-y-1">
                                <div class="flex items-center flex-wrap gap-2">
                                    <strong class="text-lg font-semibold tracking-wide text-gray-800 uppercase">{{ $alert->nopol }}</strong>
                                    <span class="text-xs font-semibold px-2 py-0.5 rounded-md {{ $badgeClass }}">{{ $alert->tipe }}</span>
                                </div>
                                <p class="text-sm text-gray-500">
                                    {{ $alert->jenis_layanan }} &bull; 
                                    <span class="font-semibold {{ $statusClass }}">{{ $alert->status_teks }}</span>
                                </p>
                                <p class="text-xs text-gray-400">{{ \Carbon\Carbon::parse($alert->tanggal_asli)->format('d M Y') }}</p>

                                @if($alert->kategori_warna != 'info' && empty($alert->email))
                                    <p class="text-xs text-orange-600 flex items-center gap-1.5 pt-1">
                                        <i class="fa-solid fa-triangle-exclamation"></i> Email klien belum tersedia
                                    </p>
                                @endif
                            </div>
                        </div>

                        <!-- Tombol Action -->
                        <div class="grid grid-cols-2 gap-2 pl-12">
                            @if($alert->kategori_warna != 'info' && !empty($alert->email))
                                <a href="{{ route('reminder.send', $alert->vehicle_id) }}" 
                                    onclick="return confirm('Kirim email peringatan pajak ke {{ $alert->nopol }}?')"
                                    class="w-full justify-center px-3 py-2 bg-white border border-gray-300 hover:bg-gray-50 text-gray-600 text-xs font-medium rounded-lg transition-colors flex items-center gap-1.5">
                                        <i class="fa-regular fa-envelope"></i> <span>Kirim Notif</span>
                                    </a>
                            @elseif($alert->kategori_warna != 'info')
                                <div></div>
                            @endif

                            <a href="{{ route('transactions.index', ['search' => $alert->nopol]) }}" 
                            class="w-full justify-center px-3 py-2 {{ $btnClass }} text-xs font-semibold rounded-lg shadow-sm transition-colors flex items-center gap-1.5 {{ $alert->kategori_warna == 'info' ? 'col-span-2' : '' }}">
                                <i class="fa-solid fa-bolt"></i> <span>{{ $btnText }}</span>
                            </a>
                        </div>
                    </div>

                @empty
                    <!-- Kondisi Jika Data Kosong (Aman) -->
                    <div class="col-span-full py-12 text-center">
                        <div class="text-5xl mb-4 text-green-500 opacity-50">
                            <i class="fa-solid fa-circle-check"></i>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-800 mb-1">Semua Kendaraan Aman</h3>
                        <p class="text-gray-500">Tidak ada STNK yang mendekati jatuh tempo dalam waktu dekat.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
    @if($alerts->hasPages())
    <div class="flex items-center justify-between border-t border-gray-300 px-6 py-5">
        
        {{-- Jumlah Data --}}
        <p class="text-sm text-gray-500">
            Menampilkan
            <span class="font-medium text-gray-700">
                {{ $alerts->firstItem() }}
            </span>
            -
            <span class="font-medium text-gray-700">
                {{ $alerts->lastItem() }}
            </span>
            dari
            <span class="font-medium text-gray-700">
                {{ $alerts->total() }}
            </span>
            data
        </p>

        {{-- Pagination --}}
        <div class="flex items-center gap-2">
            {{-- Previous --}}
            @if($alerts->onFirstPage())
                <span class="flex h-10 w-10 items-center justify-center rounded-lg border border-gray-300 text-gray-300">
                    ‹
                </span>
            @else
                <a href="{{ $alerts->previousPageUrl() }}"
                   class="flex h-10 w-10 items-center justify-center rounded-lg border border-gray-300 text-gray-600 transition hover:bg-gray-50">
                    ‹
                </a>
            @endif

            {{-- Nomor Halaman --}}
            @for($page = 1; $page <= $alerts->lastPage(); $page++)
                @if($page == $alerts->currentPage())
                    <span class="flex h-10 w-10 items-center justify-center rounded-lg bg-blue-600 text-sm font-semibold text-white">
                        {{ $page }}
                    </span>
                @else
                    <a href="{{ $alerts->url($page) }}"
                       class="flex h-10 w-10 items-center justify-center rounded-lg border border-gray-300 text-sm text-gray-600 transition hover:bg-gray-50">
                        {{ $page }}
                    </a>
                @endif
            @endfor

            {{-- Next --}}
            @if($alerts->hasMorePages())
                <a href="{{ $alerts->nextPageUrl() }}"
                   class="flex h-10 w-10 items-center justify-center rounded-lg border border-gray-300 text-gray-600 transition hover:bg-gray-50">
                    ›
                </a>
            @else
                <span class="flex h-10 w-10 items-center justify-center rounded-lg border border-gray-300 text-gray-300">
                    ›
                </span>
            @endif
        </div>
    </div>
@endif
    @endsection