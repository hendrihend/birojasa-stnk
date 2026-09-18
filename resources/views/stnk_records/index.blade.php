@extends('layouts.app')

@section('title', 'Manajemen Pajak & STNK')
@section('header_title', 'Jadwal Jatuh Tempo Pajak')

@section('content')

    <!-- Filter Bar -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-300 p-4 mb-6">
        <form action="{{ route('stnk_records.index') }}" method="GET" class="flex flex-col lg:flex-row lg:items-end gap-3">
            
            <!-- Search Nopol / No. STNK -->
            <div class="flex-1 min-w-[220px]">
                <label class="block text-xs font-semibold text-gray-500 mb-1">Cari Nopol / No. STNK</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                        <i class="fa-solid fa-magnifying-glass text-gray-400 text-sm"></i>
                    </div>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari Nopol atau No. STNK..." 
                        class="w-full pl-9 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-shadow">
                </div>
            </div>

            <!-- Filter Status -->
            <div class="w-full lg:w-44">
                <label class="block text-xs font-semibold text-gray-500 mb-1">Status</label>
                <select name="status" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none bg-white">
                    <option value="">Semua Status</option>
                    <option value="aktif" {{ request('status') == 'aktif' ? 'selected' : '' }}>Aktif</option>
                    <option value="nonaktif" {{ request('status') == 'nonaktif' ? 'selected' : '' }}>Nonaktif</option>
                </select>
            </div>

            <!-- Filter Rentang Tanggal Jatuh Tempo Pajak -->
            <div class="w-full lg:w-44">
                <label class="block text-xs font-semibold text-gray-500 mb-1">Jatuh Tempo Dari</label>
                <input type="date" name="tgl_dari" value="{{ request('tgl_dari') }}" 
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none">
            </div>
            <div class="w-full lg:w-44">
                <label class="block text-xs font-semibold text-gray-500 mb-1">Sampai</label>
                <input type="date" name="tgl_sampai" value="{{ request('tgl_sampai') }}" 
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none">
            </div>

            <!-- Tombol Aksi Filter -->
            <div class="flex gap-2 shrink-0">
                <button type="submit" class="px-5 py-2 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg shadow-sm transition-colors flex items-center gap-2">
                    <i class="fa-solid fa-filter text-sm"></i> Filter
                </button>
                @if(request('search') || request('status') || request('tgl_dari') || request('tgl_sampai'))
                    <a href="{{ route('stnk_records.index') }}" class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-600 font-semibold rounded-lg transition-colors flex items-center">
                        <i class="fa-solid fa-rotate-left"></i>
                    </a>
                @endif
            </div>
        </form>
    </div>

    <!-- Tabel Data STNK -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-300 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse table-fixed">
                <thead>
                    <tr class="bg-gray-50 border-b border-gray-300 text-sm text-gray-600 uppercase tracking-wider">
                        <th class="p-4 font-semibold w-[22%]">Kendaraan</th>
                        <th class="p-4 font-semibold w-[14%]">Nomor STNK</th>
                        <th class="p-4 font-semibold w-[18%]">Jatuh Tempo Pajak</th>
                        <th class="p-4 font-semibold w-[16%]">Jatuh Tempo Kaleng</th>
                        <th class="p-4 font-semibold text-center w-[14%]">Status</th>
                        @if(Auth::user()->role === 'super_admin')
                        <th class="p-4 font-semibold text-center w-[16%]">Aksi</th>
                        @endif
                    </tr>
                </thead>
                <tbody class="text-sm divide-y divide-gray-100">
                    @forelse($records as $record)
                    
                    @php
                        // Logika Perhitungan Hari & Warna Teks (background baris tidak lagi diwarnai)
                        $tglPajak = \Carbon\Carbon::parse($record->tgl_jatuh_tempo_pajak);
                        $hariIni = \Carbon\Carbon::now()->startOfDay();
                        $sisaHari = $hariIni->diffInDays($tglPajak, false);
                        
                        $textDateColor = 'text-green-600'; // Default Aman
                        $badgeSisaHari = 'bg-green-50 text-green-700';
                        
                        if($sisaHari < 0) {
                            $textDateColor = 'text-red-600';
                            $badgeSisaHari = 'bg-red-50 text-red-700';
                        } elseif ($sisaHari <= 30) {
                            $textDateColor = 'text-orange-600';
                            $badgeSisaHari = 'bg-orange-50 text-orange-700';
                        }
                    @endphp

                    <tr class="hover:bg-gray-50/80 transition-colors">
                        <td class="p-4">
                            <strong class="text-gray-800 text-base block">{{ preg_replace('/([A-Z]+)(\d+)([A-Z]+)/', '$1 $2 $3', strtoupper($record->vehicle->nopol)) ?? '-' }}</strong>
                            <span class="text-xs text-gray-500">{{ $record->vehicle->client->nama_lengkap ?? '-' }}</span>
                        </td>
                        <td class="p-4 text-gray-700 font-medium tracking-wide">{{ $record->no_stnk }}</td>
                        
                        <td class="p-4">
                            <strong class="{{ $textDateColor }} block text-base">{{ $tglPajak->format('d M Y') }}</strong>
                            <span class="text-xs font-semibold px-2 py-0.5 rounded mt-1 inline-block {{ $badgeSisaHari }}">
                                {{ $sisaHari < 0 ? 'Terlewat ' . abs($sisaHari) . ' hari' : ($sisaHari == 0 ? 'Hari ini!' : $sisaHari . ' hari lagi') }}
                            </span>
                        </td>
                        
                        <td class="p-4 text-gray-600 font-medium">{{ \Carbon\Carbon::parse($record->tgl_habis_stnk)->format('d M Y') }}</td>
                        
                        <td class="p-4 text-center">
                            @if($record->status_aktif)
                                <span class="px-3 py-1 bg-green-50 text-green-700 border border-green-200 text-xs font-semibold rounded-full">Aktif</span>
                            @else
                                <span class="px-3 py-1 bg-gray-100 text-gray-500 border border-gray-200 text-xs font-semibold rounded-full">Nonaktif</span>
                            @endif
                        </td>
                        
                        @if(Auth::user()->role === 'super_admin')
                        <td class="p-4 text-center">
                            <form action="{{ route('stnk_records.destroy', $record->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus data STNK ini?');" class="inline">
                                @csrf @method('DELETE')
                                <button type="submit" class="px-3 py-1.5 bg-red-50 hover:bg-red-100 text-red-600 text-xs font-semibold rounded-lg transition-colors flex items-center gap-1.5 mx-auto">
                                    <i class="fa-solid fa-trash-can"></i> Hapus
                                </button>
                            </form>
                        </td>
                        @endif
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="p-10 text-center text-gray-500 text-base">Belum ada data STNK/Pajak yang tercatat.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <!-- Paginations -->
        @if($records->hasPages())
            <div class="px-5 py-4 border-t border-gray-300 flex flex-col sm:flex-row justify-between items-center gap-3">
                <div class="text-sm text-gray-500">
                    Menampilkan <span class="font-semibold text-gray-700">{{ $records->firstItem() }}</span> - <span class="font-semibold text-gray-700">{{ $records->lastItem() }}</span> dari <span class="font-semibold text-gray-700">{{ $records->total() }}</span> data
                </div>
                <div class="flex items-center gap-1">
                    @if($records->onFirstPage())
                        <span class="px-3 py-2 border border-gray-200 rounded-lg text-gray-300 bg-gray-50">
                            <i class="fa-solid fa-chevron-left"></i>
                        </span>
                    @else
                        <a href="{{ $records->withQueryString()->previousPageUrl() }}" class="px-3 py-2 border border-gray-200 rounded-lg text-gray-600 bg-white hover:bg-blue-50 hover:text-blue-700 transition-colors">
                            <i class="fa-solid fa-chevron-left"></i>
                        </a>
                    @endif

                    @foreach($records->getUrlRange(1, $records->lastPage()) as $page => $url)
                        @if($page == $records->currentPage())
                            <span class="px-3 py-2 rounded-lg bg-blue-600 text-white font-semibold">{{ $page }}</span>
                        @else
                            <a href="{{ $records->withQueryString()->url($page) }}" class="px-3 py-2 border border-gray-200 rounded-lg text-gray-600 bg-white hover:bg-blue-50 hover:text-blue-700 transition-colors">{{ $page }}</a>
                        @endif
                    @endforeach

                    @if($records->hasMorePages())
                        <a href="{{ $records->withQueryString()->nextPageUrl() }}" class="px-3 py-2 border border-gray-200 rounded-lg text-gray-600 bg-white hover:bg-blue-50 hover:text-blue-700 transition-colors">
                            <i class="fa-solid fa-chevron-right"></i>
                        </a>
                    @else
                        <span class="px-3 py-2 border border-gray-200 rounded-lg text-gray-300 bg-gray-50">
                            <i class="fa-solid fa-chevron-right"></i>
                        </span>
                    @endif
                </div>
            </div>
        @endif
    </div>
@endsection