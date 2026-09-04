@extends('layouts.app')

@section('title', 'Status Pengurusan')
@section('header_title', 'Daftar Transaksi & Pengurusan')

@section('content')

    <!-- Action Bar -->
    <div class="flex flex-col md:flex-row justify-normal items-center gap-4 mb-6">
        <a href="{{ route('transactions.create') }}" class="px-5 py-2.5 bg-gray-900 hover:bg-gray-800 text-white font-bold rounded-lg shadow-sm transition-colors w-full md:w-auto text-center">
            + Buat Transaksi Baru
        </a>
        <button type="button" id="btnCetakKolektif" class="px-5 py-2.5 bg-blue-900 hover:bg-blue-800 text-white font-bold rounded-lg shadow-sm transition-colors w-full md:w-auto text-center">
            <i class="fa-solid fa-layer-group"></i> Cetak Invoice Kolektif
        </button>
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
                        <th class="p-4 text-left w-10">
                            <input type="checkbox" id="checkAll" class="rounded border-gray-300">
                        </th>
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
                            @if($trx->status_proses !== 'Cancel' && $trx->status_proses !== 'Pending')
                                <input type="checkbox" value="{{ $trx->id }}" class="trx-checkbox rounded border-gray-300">
                            @endif
                        </td>
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
                            <a href="{{ route('transactions.print', $trx->id) }}" target="_blank" 
                            class="{{ $trx->status_proses == 'Pending' ? 'pointer-events-none opacity-40 select-none' : '' }} &&
                                    {{ $trx->status_proses == 'Cancel' ? 'pointer-events-none opacity-40 select-none' : '' }} 
                                    inline-flex items-center justify-center w-8 h-8 hover:bg-gray-200 text-gray-700 transition-colors" title="Cetak Tanda Terima">
                                <i class="fa-solid fa-print"></i>
                            </a>
                            <a href="{{ route('transactions.edit', $trx->id) }}" class="{{ $trx->status_proses == 'Cancel' ? 'pointer-events-none opacity-40 select-none' : '' }} text-blue-700 hover:text-blue-700 font-bold px-2 py-1 hover:bg-blue-200 rounded transition-colors flex items-center gap-1">
                                <i class="fa-solid fa-pen-to-square"></i>
                            </a>
                            
                            @if(Auth::user()->role === 'super_admin')
                                <form action="{{ route('transactions.destroy', $trx->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus transaksi ini?');" class="inline-flex items-center justify-center w-8 h-8">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="text-red-700 hover:text-red-700 font-bold px-2 py-1 hover:bg-red-200 rounded transition-colors flex items-center gap-1">
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

        <!-- Paginations -->
        @if($transactions->hasPages())
            <div class="px-5 py-4 border-t border-gray-200 flex flex-col sm:flex-row justify-between items-center gap-3">
                <div class="text-sm text-gray-500">
                    Menampilkan <span class="font-semibold text-gray-700">{{ $transactions->firstItem() }}</span> - <span class="font-semibold text-gray-700">{{ $transactions->lastItem() }}</span> dari <span class="font-semibold text-gray-700">{{ $transactions->total() }}</span> data
                </div>
                <div class="flex items-center gap-1">
                    @if($transactions->onFirstPage())
                        <span class="px-3 py-2 border border-gray-200 rounded-lg text-gray-300 bg-gray-50">
                            <i class="fa-solid fa-chevron-left"></i>
                        </span>
                    @else
                        <a href="{{ $transactions->withQueryString()->previousPageUrl() }}" class="px-3 py-2 border border-gray-200 rounded-lg text-gray-600 bg-white hover:bg-blue-80 hover:text-blue-700 transition-colors">
                            <i class="fa-solid fa-chevron-left"></i>
                        </a>
                    @endif

                    @foreach($transactions->getUrlRange(1, $transactions->lastPage()) as $page => $url)
                        @if($page == $transactions->currentPage())
                            <span class="px-3 py-2 rounded-lg bg-blue-900 text-white font-semibold">{{ $page }}</span>
                        @else
                            <a href="{{ $transactions->withQueryString()->url($page) }}" class="px-3 py-2 border border-gray-200 rounded-lg text-gray-600 bg-white hover:bg-blue-80 hover:text-blue-700 transition-colors">{{ $page }}</a>
                        @endif
                    @endforeach

                    @if($transactions->hasMorePages())
                        <a href="{{ $transactions->withQueryString()->nextPageUrl() }}" class="px-3 py-2 border border-gray-200 rounded-lg text-gray-600 bg-white hover:bg-blue-80 hover:text-blue-700 transition-colors">
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

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const checkAll = document.getElementById('checkAll');
            const checkboxes = document.querySelectorAll('.trx-checkbox');
            const btnCetak = document.getElementById('btnCetakKolektif');

            // Pastikan tombol ditemukan di halaman
            if (!btnCetak) return;

            // Fitur Cetak All
            if (checkAll) {
                checkAll.addEventListener('change', function() {
                    checkboxes.forEach(cb => cb.checked = this.checked);
                });
            }

           // Fitur Klik Cetak
            btnCetak.addEventListener('click', function(e) {
                e.preventDefault(); // Mencegah tombol me-refresh halaman
                
                let selectedIds = [];
                checkboxes.forEach(cb => {
                    if(cb.checked) {
                        selectedIds.push(cb.value);
                    }
                });

                if(selectedIds.length === 0) {
                    Swal.fire('Oops!', 'Pilih minimal satu transaksi dulu!', 'warning');
                    return;
                }

                // Kumpulkan ID dan buka tab baru
                let queryString = selectedIds.map(id => 'ids[]=' + id).join('&');
                let urlCetak = "{{ route('transactions.print-bulk') }}?" + queryString;
                
                window.open(urlCetak, '_blank');
            });



        });
    </script>




@endsection
