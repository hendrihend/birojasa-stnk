@extends('layouts.app')

@section('title', 'Status Pengurusan')
@section('header_title', 'Daftar Transaksi & Pengurusan')

@section('content')
    <!-- Pesan Sukses -->
    @if(session('success'))
        <div class="mb-5 px-4 py-3 bg-green-100 border-l-4 border-green-500 text-green-700 rounded shadow-sm flex items-center">
            <span class="mr-2">✅</span> {{ session('success') }}
        </div>
    @endif

    <!-- Action Bar -->
    <div class="flex flex-col md:flex-row justify-between items-center gap-4 mb-6">
        <a href="{{ route('transactions.create') }}" class="px-5 py-2.5 bg-gray-900 hover:bg-gray-800 text-white font-bold rounded-lg shadow-sm transition-colors w-full md:w-auto text-center">
            + Buat Transaksi Baru
        </a>

        <!-- Kotak Pencarian -->
        <form action="{{ route('transactions.index') }}" method="GET" class="flex w-full md:w-auto gap-2">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari No. Invoice atau Nopol..." class="w-full md:w-80 px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-shadow">
            
            @if(request('search'))
                <a href="{{ route('transactions.index') }}" class="px-4 py-2 bg-red-100 hover:bg-red-200 text-red-600 font-bold rounded-lg transition-colors flex items-center">Reset</a>
            @endif
        </form>
    </div>

    <!-- Tabel Data Transaksi -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50 border-b border-gray-200 text-sm text-gray-600 uppercase tracking-wider">
                        <th class="p-4 font-bold">No. Invoice & Tgl</th>
                        <th class="p-4 font-bold">Kendaraan</th>
                        <th class="p-4 font-bold">Layanan</th>
                        <th class="p-4 font-bold text-center">Status</th>
                        <th class="p-4 font-bold text-right">Total Biaya</th>
                        <th class="p-4 font-bold text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="text-sm divide-y divide-gray-100">
                    @forelse($transactions as $trx)
                    <tr class="hover:bg-gray-50/80 transition-colors">
                        <td class="p-4">
                            <strong class="text-gray-800 text-base block">{{ $trx->invoice_no }}</strong>
                            <span class="text-xs text-gray-500">{{ \Carbon\Carbon::parse($trx->tgl_masuk)->format('d M Y') }}</span>
                        </td>
                        <td class="p-4">
                            <span class="font-bold text-gray-800 uppercase">{{ $trx->vehicle->nopol ?? '-' }}</span><br>
                            <span class="text-xs text-gray-500">{{ $trx->vehicle->client->nama_lengkap ?? 'Tanpa Pemilik' }}</span>
                        </td>
                        <td class="p-4 text-gray-700 font-medium">{{ $trx->jenis_layanan }}</td>
                        <td class="p-4 text-center">
                            @php
                                // Logika Warna Status Tailwind
                                $statusColor = 'bg-gray-100 text-gray-700'; // Default
                                if($trx->status_proses == 'Done') $statusColor = 'bg-green-100 text-green-700 border-green-200';
                                elseif($trx->status_proses == 'Pending' || $trx->status_proses == 'Menunggu Pembayaran') $statusColor = 'bg-blue-100 text-blue-700 border-blue-200';
                                elseif($trx->status_proses == 'Cancel') $statusColor = 'bg-red-100 text-red-700 border-red-200';
                            @endphp
                            <span class="px-3 py-1 text-xs font-bold rounded-full border {{ $statusColor }}">
                                {{ $trx->status_proses }}
                            </span>
                        </td>
                        <td class="p-4 text-right font-bold text-gray-800">
                            Rp {{ number_format($trx->total_biaya, 0, ',', '.') }}
                        </td>
                        <td class="p-4 flex justify-center gap-3">
                            <a href="#" class="text-indigo-600 hover:text-indigo-800 font-bold px-2 py-1 hover:bg-indigo-50 rounded transition-colors flex items-center gap-1">
                                <i class="fa-solid fa-print"></i>
                            </a>
                            
                            <a href="{{ route('transactions.edit', $trx->id) }}" class="text-blue-500 hover:text-blue-700 font-bold px-2 py-1 hover:bg-blue-50 rounded transition-colors flex items-center gap-1">
                                <i class="fa-solid fa-pen-to-square"></i>
                            </a>
                            
                            @if(Auth::user()->role === 'super_admin')
                                <form action="{{ route('transactions.destroy', $trx->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus transaksi ini?');" class="inline">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="text-red-500 hover:text-red-700 font-bold px-2 py-1 hover:bg-red-50 rounded transition-colors flex items-center gap-1">
                                        <i class="fa-solid fa-trash-can"></i>
                                    </button>
                                </form>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="p-10 text-center text-gray-500 text-base">Belum ada data transaksi yang tercatat.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
