<!-- Master Layout -->
@extends('layouts.app')

<!-- title -->
@section('title', 'Dashboard')

<!-- Header Content -->
@section('header_title', 'Dashboard')

  <!-- Main Content -->
  @section('content')
  <!-- Kolom Pencarian Cepat (Quick Search) -->
    <div class="mb-8">
        <!-- Ubah route-nya menjadi 'dashboard' -->
        <form action="{{ route('dashboard') }}" method="GET" class="relative group">
            <div class="absolute inset-y-0 left-0 flex items-center pl-5 pointer-events-none">
                <i class="fa-solid fa-magnifying-glass text-gray-400 group-focus-within:text-blue-500 transition-colors text-lg"></i>
            </div>
            
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Pencarian cepat: Ketik Nomor Polisi atau Nama Klien di sini..." 
                class="w-full pl-14 pr-32 py-4 bg-white border border-gray-100 shadow-sm rounded-xl text-gray-700 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none text-base transition-all hover:shadow-md">
            
            <div class="absolute inset-y-2 right-2 flex gap-2">
                @if(request('search'))
                    <!-- Tombol Reset X -->
                    <a href="{{ route('dashboard') }}" class="px-4 py-2 text-gray-500 hover:text-red-500 hover:bg-red-50 font-bold rounded-lg transition-colors flex items-center">
                        <i class="fa-solid fa-xmark text-xl"></i>
                    </a>
                @endif
                <button type="submit" class="px-6 bg-gray-900 hover:bg-gray-800 text-white font-bold rounded-lg transition-colors flex items-center gap-2">
                    <span>Cari</span>
                </button>
            </div>
        </form>
    </div>

    <!-- BLOK HASIL PENCARIAN (Hanya Muncul Jika Ada Kata Kunci) -->
    @if(request('search'))
        <div class="mb-8 bg-white rounded-xl shadow-sm border border-blue-200 overflow-hidden ring-1 ring-blue-100">
            <div class="p-4 border-b border-gray-100 bg-blue-50/50 flex justify-between items-center">
                <h2 class="font-bold text-gray-800">
                    <i class="fa-solid fa-list-check text-blue-500 mr-2"></i> Hasil Pencarian: "{{ request('search') }}"
                </h2>
                <span class="text-xs font-bold bg-blue-100 text-blue-700 px-3 py-1 rounded-full">{{ $searchResults->count() }} Ditemukan</span>
            </div>
            
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <tbody class="text-sm divide-y divide-gray-100">
                        @forelse($searchResults as $vehicle)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="p-4 font-black text-gray-800 text-base w-32">{{ $vehicle->nopol }}</td>
                            <td class="p-4 text-gray-700">{{ $vehicle->merk }} {{ $vehicle->tipe }}</td>
                            <td class="p-4 text-gray-600">
                                <span class="font-bold text-blue-700 bg-blue-50 border border-blue-100 px-3 py-1 rounded-full text-xs">
                                    <i class="fa-solid fa-user"></i> {{ $vehicle->client->nama_lengkap ?? 'Tanpa Pemilik' }}
                                </span>
                            </td>
                            <td class="p-4 flex justify-end gap-3">
                                <a href="{{ route('transactions.create', ['vehicle_id' => $vehicle->id]) }}" class="text-xs px-3 py-1.5 bg-gray-900 text-white font-bold rounded hover:bg-gray-800 transition-colors">
                                    <i class="fa-solid fa-plus mr-1"></i> Transaksi
                                </a>
                                <a href="{{ route('vehicles.show', $vehicle->id) }}" class="text-xs px-3 py-1.5 bg-blue-100 text-blue-700 font-bold rounded hover:bg-blue-200 transition-colors">
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
    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6 mb-8">
        <!-- Card 1: Kendaraan -->
        <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100 flex items-center gap-5 hover:shadow-md transition-shadow">
            <div class="w-14 h-14 rounded-full bg-blue-50 flex items-center justify-center text-blue-500 text-2xl">
                <i class="fa-solid fa-car"></i>
            </div>
            <div>
                <p class="text-sm font-medium text-gray-500 mb-1">Total Kendaraan</p>
                <h3 class="text-2xl font-black text-gray-800">{{ $totalKendaraan ?? 0 }}</h3>
            </div>
        </div>

        <!-- Card 2: STNK Aktif -->
        <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100 flex items-center gap-5 hover:shadow-md transition-shadow">
            <div class="w-14 h-14 rounded-full bg-blue-50 flex items-center justify-center text-blue-500 text-2xl">
                <i class="fa-solid fa-file-circle-check"></i>
            </div>
            <div>
                <p class="text-sm font-medium text-gray-500 mb-1">STNK Aktif</p>
                <h3 class="text-2xl font-black text-gray-800">{{ $stnkAktif ?? 0 }}</h3>
            </div>
        </div>

        <!-- Card 3: Total Pengeluaran -->
        <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100 flex items-center gap-5 hover:shadow-md transition-shadow">
            <div class="w-14 h-14 rounded-full bg-blue-50 flex items-center justify-center text-blue-500 text-2xl">
                <i class="fa-solid fa-wallet"></i>
            </div>
            <div>
                <p class="text-sm font-medium text-gray-500 mb-1">Total Pengeluaran</p>
                <h3 class="text-2xl font-black text-gray-800 number">Rp {{ number_format($totalPengeluaran, 0, ',', '.' ?? 0) }}</h3>
            </div>
        </div>

        <!-- Card 4: Kendaraan Diproses -->
        <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100 flex items-center gap-5 hover:shadow-md transition-shadow">
            <div class="w-14 h-14 rounded-full bg-blue-50 flex items-center justify-center text-blue-500 text-2xl">
                <i class="fa-solid fa-hourglass-half"></i>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-500 mb-1">Kendaraan Diproses</p>
                    <h3 class="text-2xl font-black text-gray-800">{{ $kendaraanDiproses ?? 0 }}</h3>
                </div>
                </div>
        
        <!-- Card 5: Kendaraan Selesai Diproses -->
        <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100 flex items-center gap-5 hover:shadow-md transition-shadow">
            <div class="w-14 h-14 rounded-full bg-green-50 flex items-center justify-center text-green-500 text-2xl">
                <div class="fa-solid fa-hourglass-end"></div>
            </div>
            <div>
                <p class="text-sm font-medium text-gray-500 mb-1">Kendaraan Selesai Diproses</p>
                <h3 class="text-2xl font-black text-gray-800">{{ $kendaraanSelesaiDiproses ?? 0 }}</h3>
            </div>
        </div>

        <!-- Card 6: Kendaraan Cancel -->
        <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100 flex items-center gap-5 hover:shadow-md transition-shadow">
            <div class="w-14 h-14 rounded-full bg-red-50 flex items-center justify-center text-red-500 text-2xl">
                <div class="fa-solid fa-circle-xmark"></div>
            </div>
            <div>
                <p class="text-sm font-medium text-gray-500 mb-1">Kendaraan Cancel</p>
                <h3 class="text-2xl font-black text-gray-800">{{ $kendaraanCancel ?? 0 }}</h3>
            </div>
        </div>


    </div>

    <!-- Section Peringatan Jatuh Tempo -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        
        <!-- Header Section -->
        <div class="p-6 border-b border-gray-100 flex justify-between items-center bg-gray-50/50">
            <h2 class="text-lg font-bold text-gray-800">
                <i class="fa-regular fa-bell text-red-500 mr-2"></i> Peringatan Jatuh Tempo Pajak
            </h2>
            <span class="bg-red-100 text-red-700 text-xs font-bold px-3 py-1 rounded-full">{{ count($alerts ?? []) }} Peringatan</span>
        </div>
        
        <!-- Isi Peringatan (Grid 2 Kolom) -->
        <div class="p-6">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
                @forelse($alerts as $alert)
                    @php
                        // Logika untuk mengubah kategori warna dari Controller menjadi Class Tailwind
                        $bgClass = 'bg-gray-50 border-gray-200';
                        $textClass = 'text-gray-800';
                        $badgeClass = 'bg-gray-200 text-gray-700';
                        $btnClass = 'bg-gray-800 hover:bg-gray-900 text-white'; // Tombol default
                        $btnText = 'Urus Sekarang';
                        
                        if($alert->kategori_warna == 'danger' || $alert->kategori_warna == 'merah') {
                            $bgClass = 'bg-red-50 border-red-200';
                            $textClass = 'text-red-800';
                            $badgeClass = 'bg-red-200 text-red-800';
                            $btnClass = 'bg-red-500 hover:bg-red-600 text-white';
                        } elseif($alert->kategori_warna == 'warning' || $alert->kategori_warna == 'kuning') {
                            $bgClass = 'bg-yellow-50 border-yellow-200';
                            $textClass = 'text-yellow-900';
                            $badgeClass = 'bg-yellow-200 text-yellow-900';
                            $btnClass = 'bg-yellow-500 hover:bg-yellow-600 text-white';
                        } elseif($alert->kategori_warna == 'info') {
                            // Warna biru khusus untuk kendaraan "SEDANG DIURUS"
                             $bgClass = 'bg-blue-50 border-blue-200';
                             $textClass = 'text-blue-800';
                             $badgeClass = 'bg-blue-200 text-blue-800';
                             $btnClass = 'bg-blue-600 hover:bg-blue-700 text-white';
                             $btnText = 'Cek Transaksi'; // ubah teks tombolnya
                        }
                    @endphp

                    <!-- Card Peringatan Individual -->
                    <div class="border rounded-xl p-5 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 transition-transform hover:scale-[1.01] {{ $bgClass }}">
                        <div>
                            <div class="flex items-center gap-3 mb-2">
                                <strong class="text-xl tracking-wide {{ $textClass }} uppercase">{{ $alert->nopol }}</strong>
                                <span class="text-xs font-bold px-2 py-1 rounded-md {{ $badgeClass }}">{{ $alert->tipe }}</span>
                            </div>
                            <p class="text-sm {{ $textClass }} opacity-90">
                                {{ $alert->layanan }} &bull; <span class="font-bold">{{ $alert->status_teks }}</span> 
                                ({{ \Carbon\Carbon::parse($alert->tanggal_asli)->format('d M Y') }})
                            </p>
                        </div>
                        
                        <!-- Tombol Action -->
                         <a href="{{ route('transactions.index', ['search' => $alert->nopol]) }}" 
                           class="shrink-0 px-5 py-2.5 {{ $btnClass }} text-sm font-bold rounded-lg shadow-sm hover:shadow transition-all duration-200 flex items-center gap-2">
                            <i class="fa-solid fa-bolt"></i> <span>{{ $btnText }}</span>
                        </a>
                        <!-- <a href="{{ route('transactions.create', ['vehicle_id' => $alert->vehicle_id]) }}" 
                           class="shrink-0 px-5 py-2.5 bg-red-500 hover:bg-red-600 text-white text-sm font-bold rounded-lg shadow-sm hover:shadow transition-all duration-200 flex items-center gap-2">
                            <i class="fa-solid fa-bolt"></i> <span>Urus Sekarang</span>
                        </a> -->
                    </div>

                @empty
                    <!-- Kondisi Jika Data Kosong (Aman) -->
                    <div class="col-span-full py-12 text-center">
                        <div class="text-5xl mb-4 text-green-500 opacity-50">
                            <i class="fa-solid fa-circle-check"></i>
                        </div>
                        <h3 class="text-lg font-bold text-gray-800 mb-1">Semua Kendaraan Aman</h3>
                        <p class="text-gray-500">Tidak ada STNK yang mendekati jatuh tempo dalam waktu dekat.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
    @endsection

   