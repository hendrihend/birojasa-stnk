@extends('layouts.app')

@section('title', 'Daftar Transaksi')
@section('header_title', 'Daftar Transaksi & Pengurusan')

@section('content')

    <?php
        // FUNGSI UNTUK MENGUBAH ANGKA MENJADI TEKS (TERBILANG)
        if (!function_exists('penyebut')) {
            function penyebut($nilai) {
                $nilai = abs($nilai);
                $huruf = array("", "Satu", "Dua", "Tiga", "Empat", "Lima", "Enam", "Tujuh", "Delapan", "Sembilan", "Sepuluh", "Sebelas");
                $temp = "";
                if ($nilai < 12) { $temp = " ". $huruf[$nilai]; } 
                else if ($nilai <20) { $temp = penyebut($nilai - 10). " Belas"; } 
                else if ($nilai < 100) { $temp = penyebut($nilai/10)." Puluh". penyebut($nilai % 10); } 
                else if ($nilai < 200) { $temp = " Seratus" . penyebut($nilai - 100); } 
                else if ($nilai < 1000) { $temp = penyebut($nilai/100) . " Ratus" . penyebut($nilai % 100); } 
                else if ($nilai < 2000) { $temp = " Seribu" . penyebut($nilai - 1000); } 
                else if ($nilai < 1000000) { $temp = penyebut($nilai/1000) . " Ribu" . penyebut($nilai % 1000); } 
                else if ($nilai < 1000000000) { $temp = penyebut($nilai/1000000) . " Juta" . penyebut($nilai % 1000000); } 
                return $temp;
            }
        }
        
        if (!function_exists('terbilang')) {
            function terbilang($nilai) {
                if($nilai == 0) return "Nol Rupiah";
                if($nilai < 0) { $hasil = "Minus ". trim(penyebut($nilai)); } 
                else { $hasil = trim(penyebut($nilai)); }
                return $hasil . " Rupiah";
            }
        }
    ?>

    <!-- Action Bar -->
    <div class="flex flex-col md:flex-row justify-normal items-center gap-4 mb-6">
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
                        <th colspan="2" class="p-4 font-bold">No. Invoice & Tgl</th>
                        <th class="p-4 font-bold">Kendaraan</th>
                        <th class="p-4 font-bold">Layanan</th>
                        <th class="p-4 font-bold text-center">Status</th>
                        <th class="p-4 font-bold text-right">Total Biaya</th>
                        <th class="p-4 font-bold text-center">Aksi</th>
                    </tr>
                </thead>
                <!-- ========================================================== -->
                <!-- LOGIKA GROUPING TRANSAKSI KOLEKTIF                         -->
                <!-- ========================================================== -->
                @php
                    // Kelompokkan data berdasarkan Base Invoice 
                    // (Menghapus angka -1, -2, dst di belakang nomor invoice)
                    $groupedTransactions = $transactions->groupBy(function($trx) {
                        return preg_replace('/-\d+$/', '', $trx->invoice_no);
                    });
                @endphp

                <tbody class="text-sm divide-y divide-gray-100">
                    @forelse($groupedTransactions as $baseInvoice => $trxs)
                        @php
                            $firstTrx = $trxs->first();
                            $jumlahKendaraan = $trxs->count();
                            $totalBiayaGroup = $trxs->sum('total_biaya');
                            
                            // Evaluasi Status Gabungan
                            if ($trxs->every(fn($t) => $t->status_proses == 'Done')) {
                                $statusGroup = 'Done';
                                $statusColor = 'bg-green-100 text-green-700 border-green-200';
                            } elseif ($trxs->every(fn($t) => $t->status_proses == 'Cancel')) {
                                $statusGroup = 'Cancel';
                                $statusColor = 'bg-red-100 text-red-700 border-red-200';
                            } else {
                                $statusGroup = 'Pending'; // Jika ada campuran Pending & Done
                                $statusColor = 'bg-blue-100 text-blue-700 border-blue-200';
                            }

                        @endphp

                        <!-- ========================================================== -->
                        <!-- 1. BARIS INDUK (SUMMARY INVOICE)                           -->
                        <!-- ========================================================== -->
                        <tr class="hover:bg-gray-50/80 transition-colors bg-white">
                            <td colspan="2" class="p-4">
                                <strong class="text-gray-800 text-base block">{{ $baseInvoice }}</strong>
                                <span class="text-xs text-gray-500">{{ \Carbon\Carbon::parse($firstTrx->tgl_masuk)->format('d M Y') }}</span>
                            </td>
                            <td class="p-4">
                                @if($jumlahKendaraan > 1)
                                    <span class="font-bold text-blue-700 bg-blue-50 px-2 py-1 rounded text-xs border border-blue-100">{{ $jumlahKendaraan }} Kendaraan</span><br>
                                    <span class="text-xs text-gray-500 mt-1 inline-block">{{ $firstTrx->vehicle->client->nama_lengkap ?? 'Klien' }}</span>
                                @else
                                    <span class="font-bold text-gray-800 uppercase">{{ $firstTrx->vehicle->nopol ?? '-' }}</span><br>
                                    <span class="text-xs text-gray-500">{{ $firstTrx->vehicle->client->nama_lengkap ?? 'Tanpa Pemilik' }}</span>
                                @endif
                            </td>
                            <td class="p-4 text-gray-700 font-medium">
                                {{ $jumlahKendaraan > 1 ? 'Layanan Kolektif' : $firstTrx->jenis_layanan }}
                            </td>
                            <td class="p-4 text-center">
                                <span class="px-3 py-1 text-xs font-bold rounded-full border {{ $statusColor }}">
                                    {{ $statusGroup }}
                                </span>
                            </td>
                            <td class="p-4 text-right font-bold text-gray-800">
                                Rp {{ number_format($totalBiayaGroup, 0, ',', '.') }}
                            </td>
                            <td class="p-4 flex justify-center gap-2 items-center">
                                
                                <!-- Tombol Lihat Rincian (Khusus Kolektif) -->
                                @if($jumlahKendaraan > 1)
                                <button type="button" onclick="toggleRincian('{{ $baseInvoice }}')" title="Lihat Detail Invoice" class="text-blue-600 hover:bg-blue-100 font-bold px-2 py-1.5 rounded transition-colors text-xs flex items-center gap-1 border border-blue-200">
                                    <i class="fa-solid fa-list-ul"></i>
                                </button>
                                @endif
                                <!-- TOMBOL LIHAT INVOICE (Pindah Halaman di Tab yang Sama) -->
                                <a href="{{ route('transactions.print', $firstTrx->id) }}" 
                                class="inline-flex items-center justify-center w-8 h-8 hover:bg-blue-100 text-blue-600 rounded transition-colors" 
                                title="Preview Invoice">
                                    <i class="fa-solid fa-eye"></i>
                                </a>

                                
                                <!-- Edit & Delete Induk (Hanya tampil jika single transaksi agar aman) -->
                                @if($jumlahKendaraan == 1)
                                    <a href="{{ route('transactions.edit', $firstTrx->id) }}" title="Edit Invoice" class="text-blue-700 font-bold px-2 py-1 hover:bg-blue-200 rounded transition-colors flex items-center gap-1">
                                        <i class="fa-solid fa-pen-to-square"></i>
                                    </a>
                                    
                                    @if(Auth::user()->role === 'super_admin')
                                        <form action="{{ route('transactions.destroy', $firstTrx->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus transaksi ini?');" class="inline-flex items-center justify-center w-8 h-8">
                                            @csrf @method('DELETE')
                                            <button type="submit" title="Hapus Invoice" class="text-red-700 font-bold px-2 py-1 hover:bg-red-200 rounded transition-colors flex items-center gap-1">
                                                <i class="fa-solid fa-trash-can"></i>
                                            </button>
                                        </form>
                                    @endif
                                @endif
                            </td>
                        </tr>

                        <!-- ========================================================== -->
                        <!-- 2. BARIS ANAK (Hanya muncul jika Kolektif & Tombol Diklik) -->
                        <!-- ========================================================== -->
                        @if($jumlahKendaraan > 1)
                            @foreach($trxs as $trx)
                            <tr class="hidden anak-row-{{ $baseInvoice }} bg-blue-50/40 text-xs border-l-4 border-blue-400 shadow-inner">
                                <td class="p-3 text-center">
                                    <i class="fa-solid fa-arrow-turn-up fa-rotate-90 text-blue-300"></i>
                                </td>
                                <td class="p-3 text-gray-500 pl-4">{{ $trx->invoice_no }}</td>
                                <td class="p-3 font-bold text-gray-700">{{ $trx->vehicle->nopol ?? '-' }}</td>
                                <td class="p-3 text-gray-600">{{ $trx->jenis_layanan }}</td>
                                <td class="p-3 text-center">
                                    @php
                                        $badgeColor = 'bg-blue-100 text-blue-700'; // Default untuk Pending
                                        
                                        if($trx->status_proses == 'Done') {
                                            $badgeColor = 'bg-green-100 text-green-700';
                                        } elseif($trx->status_proses == 'Cancel') {
                                            $badgeColor = 'bg-red-100 text-red-700';
                                        }
                                    @endphp
                                    <span class="text-[10px] font-bold px-2 py-0.5 rounded-full {{ $badgeColor }}">
                                        {{ $trx->status_proses }}
                                    </span>
                                </td>
                                <td class="p-3 text-right font-bold text-gray-700">Rp {{ number_format($trx->total_biaya, 0, ',', '.') }}</td>
                                <td class="p-3 flex justify-center gap-2">
                                    <!-- Edit & Delete per Kendaraan -->
                                    <a href="{{ route('transactions.edit', $trx->id) }}" class="text-blue-600 hover:bg-blue-200 px-1.5 py-1 rounded" title="Edit Invoice Ini"><i class="fa-solid fa-pen-to-square"></i></a>
                                    
                                    @if(Auth::user()->role === 'super_admin')
                                    <form action="{{ route('transactions.destroy', $trx->id) }}" method="POST" onsubmit="return confirm('Hapus kendaraan ini dari invoice kolektif?');" class="inline">
                                        @csrf @method('DELETE')
                                        <button type="submit" title="Hapus Invoice Ini" class="text-red-600 hover:bg-red-200 px-1.5 py-1 rounded"><i class="fa-solid fa-trash-can"></i></button>
                                    </form>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        @endif

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
        function toggleRincian(baseInvoice) {
            // Cari semua baris anak yang memiliki class dengan nama invoice tersebut
            const rows = document.querySelectorAll('.anak-row-' + baseInvoice);
            
            rows.forEach(row => {
                // Toggle class hidden bawaan Tailwind
                row.classList.toggle('hidden');
            });
        }
        
    </script>




@endsection
