@extends('layouts.app')

@section('title', 'Manajemen Pajak & STNK')
@section('header_title', 'Jadwal Jatuh Tempo Pajak')

@section('content')
    <!-- Pesan Sukses -->
    @if(session('success'))
        <div class="mb-5 px-4 py-3 bg-green-100 border-l-4 border-green-500 text-green-700 rounded shadow-sm flex items-center">
            <span class="mr-2">✅</span> {{ session('success') }}
        </div>
    @endif

    <!-- Action Bar -->
    <div class="flex flex-col md:flex-row justify-between items-center gap-4 mb-6">
        <a href="{{ route('stnk_records.create') }}" class="px-5 py-2.5 bg-gray-900 hover:bg-gray-800 text-white font-bold rounded-lg shadow-sm transition-colors w-full md:w-auto text-center">
            + Tambah Data STNK/Pajak
        </a>

        <!-- Kotak Pencarian -->
        <form action="{{ route('stnk_records.index') }}" method="GET" class="flex w-full md:w-auto gap-2">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari Nopol atau No. STNK..." class="w-full md:w-80 px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-shadow">
            
            @if(request('search'))
                <a href="{{ route('stnk_records.index') }}" class="px-4 py-2 bg-red-100 hover:bg-red-200 text-red-600 font-bold rounded-lg transition-colors flex items-center">Reset</a>
            @endif
        </form>
    </div>

    <!-- Tabel Data STNK -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50 border-b border-gray-200 text-sm text-gray-600 uppercase tracking-wider">
                        <th class="p-4 font-bold">Kendaraan</th>
                        <th class="p-4 font-bold">Nomor STNK</th>
                        <th class="p-4 font-bold">Jatuh Tempo Pajak</th>
                        <th class="p-4 font-bold">Jatuh Tempo Kaleng</th>
                        <th class="p-4 font-bold text-center">Status</th>
                        <th class="p-4 font-bold text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="text-sm divide-y divide-gray-100">
                    @forelse($records as $record)
                    
                    @php
                        // Logika Perhitungan Hari & Warna Tailwind
                        $tglPajak = \Carbon\Carbon::parse($record->tgl_jatuh_tempo_pajak);
                        $hariIni = \Carbon\Carbon::now()->startOfDay();
                        $sisaHari = $hariIni->diffInDays($tglPajak, false);
                        
                        $rowBg = 'hover:bg-gray-50/80'; // Default Putih
                        $textDateColor = 'text-green-600'; // Default Aman
                        
                        if($sisaHari < 0) {
                            $rowBg = 'bg-red-50/50 hover:bg-red-50'; // Terlewat (Merah)
                            $textDateColor = 'text-red-600';
                        } elseif ($sisaHari <= 30) {
                            $rowBg = 'bg-yellow-50/50 hover:bg-yellow-50'; // Mendekati (Kuning)
                            $textDateColor = 'text-yellow-600';
                        }
                    @endphp

                    <tr class="{{ $rowBg }} transition-colors">
                        <td class="p-4">
                            <strong class="text-gray-800 text-base block">{{ $record->vehicle->nopol ?? '-' }}</strong>
                            <span class="text-xs text-gray-500">{{ $record->vehicle->client->nama_lengkap ?? '-' }}</span>
                        </td>
                        <td class="p-4 text-gray-700 font-medium tracking-wide">{{ $record->no_stnk }}</td>
                        
                        <td class="p-4">
                            <strong class="{{ $textDateColor }} block text-base">{{ $tglPajak->format('d M Y') }}</strong>
                            <span class="text-xs font-bold text-gray-500 bg-white px-2 py-0.5 rounded shadow-sm border mt-1 inline-block">
                                {{ $sisaHari < 0 ? 'Terlewat ' . abs($sisaHari) . ' hari' : ($sisaHari == 0 ? 'Hari ini!' : $sisaHari . ' hari lagi') }}
                            </span>
                        </td>
                        
                        <td class="p-4 text-gray-600 font-medium">{{ \Carbon\Carbon::parse($record->tgl_habis_stnk)->format('d M Y') }}</td>
                        
                        <td class="p-4 text-center">
                            @if($record->status_aktif)
                                <span class="px-3 py-1 bg-green-100 text-green-700 border border-green-200 text-xs font-bold rounded-full">Aktif</span>
                            @else
                                <span class="px-3 py-1 bg-gray-100 text-gray-500 border border-gray-200 text-xs font-bold rounded-full">Nonaktif</span>
                            @endif
                        </td>
                        
                        <td class="p-4 text-center">
                            <form action="{{ route('stnk_records.destroy', $record->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus data STNK ini?');" class="inline">
                                @csrf @method('DELETE')
                                <button type="submit" class="text-red-500 hover:text-red-700 font-bold px-3 py-1 hover:bg-red-50 rounded transition-colors">Hapus</button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="p-10 text-center text-gray-500 text-base">Belum ada data STNK/Pajak yang tercatat.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection