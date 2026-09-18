@extends('layouts.app')

@section('title', 'Status Pengurusan')
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
    <div class="flex flex-col md:flex-row justify-between items-center gap-4 mb-6">
        <!-- Kotak Pencarian -->
        <form action="{{ route('transactions.index') }}" method="GET" class="flex w-full md:w-auto gap-2">
            <div class="relative w-full md:w-80">
                <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                    <i class="fa-solid fa-magnifying-glass text-gray-400 text-sm"></i>
                </div>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari No. Invoice atau Nopol..." 
                    class="w-full pl-9 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-shadow">
            </div>
            
            @if(request('search'))
                <a href="{{ route('transactions.index') }}" class="px-4 py-2 bg-red-50 hover:bg-red-100 text-red-600 font-semibold rounded-lg transition-colors flex items-center gap-1.5">
                    <i class="fa-solid fa-rotate-left"></i> Reset
                </a>
            @endif
        </form>

        <a href="{{ route('transactions.create') }}" class="px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg shadow-sm transition-colors w-full md:w-auto text-center flex items-center justify-center gap-2">
            <i class="fa-solid fa-plus"></i> Buat Transaksi Baru
        </a>
    </div>

    <!-- Tabel Data Transaksi -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-300 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50 border-b border-gray-300 text-sm text-gray-600 uppercase tracking-wider">
                        <th colspan="2" class="p-4 font-semibold">No. Invoice & Tgl</th>
                        <th class="p-4 font-semibold">Kendaraan</th>
                        <th class="p-4 font-semibold">Layanan</th>
                        <th class="p-4 font-semibold text-center">Status</th>
                        <th class="p-4 font-semibold text-right">Total Biaya</th>
                        <th class="p-4 font-semibold text-center">Aksi</th>
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
                                $statusColor = 'bg-green-50 text-green-700 border-green-200';
                            } elseif ($trxs->every(fn($t) => $t->status_proses == 'Cancel')) {
                                $statusGroup = 'Cancel';
                                $statusColor = 'bg-red-50 text-red-700 border-red-200';
                            } else {
                                $statusGroup = 'Pending'; // Jika ada campuran Pending & Done
                                $statusColor = 'bg-blue-50 text-blue-700 border-blue-200';
                            }

                            // ==========================================================
                            // DATA PREVIEW: Siapkan array data untuk pop-up JS
                            // ==========================================================
                            $previewData = [
                                'invoice_no'  => $baseInvoice,
                                'client_name' => $firstTrx->vehicle->client->nama_lengkap ?? 'Tanpa Pemilik',
                                'date'        => \Carbon\Carbon::parse($firstTrx->tgl_masuk)->format('d M Y'),
                                'grand_total' => $totalBiayaGroup,
                                'terbilang'   => terbilang($totalBiayaGroup),
                                'grand_total_lain' => $trxs->sum('biaya_lain'),
                                'rincian_lain' => [
                                    // Kalkulasi Sub-Total Rincian Biaya Lainnya
                                    'pendaftaran'     => $trxs->sum('loket_pendaftaran'),
                                    'cek_fisik'       => $trxs->sum('loket_cek_fisik'),
                                    'acc_tidak_hadir' => $trxs->sum('acc_tidak_hadir'),
                                    'acc_domisili'    => $trxs->sum('acc_domisili'),
                                    'penetapan'       => $trxs->sum('loket_penetapan'),
                                    'pengesahan_1'    => $trxs->sum('loket_pengesahan_1'),
                                    'pengesahan_2'    => $trxs->sum('loket_pengesahan_2'),
                                    'materai'         => $trxs->sum('bea_materai'),
                                ],

                                'items'       => $trxs->map(function($t) {
                                    return [
                                        'nopol'   => $t->vehicle->nopol ?? '-',
                                        'merk'    => ($t->vehicle->merk ?? '') . ' ' . ($t->vehicle->tipe ?? ''),
                                        'layanan' => $t->jenis_layanan,
                                        'pajak'   => $t->biaya_pajak,
                                        'jasa'    => $t->biaya_jasa,
                                        'lain'    => $t->biaya_lain,
                                        'total'   => $t->total_biaya,
                                    ];
                                })->toArray()
                            ];
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
                                    <span class="font-semibold text-blue-700 bg-blue-50 px-2 py-1 rounded text-xs border border-blue-100">{{ $jumlahKendaraan }} Kendaraan</span><br>
                                    <span class="text-xs text-gray-500 mt-1 inline-block">{{ $firstTrx->vehicle->client->nama_lengkap ?? 'Klien' }}</span>
                                @else
                                    <span class="font-semibold text-gray-800 uppercase">{{ $firstTrx->vehicle->nopol ?? '-' }}</span><br>
                                    <span class="text-xs text-gray-500">{{ $firstTrx->vehicle->client->nama_lengkap ?? 'Tanpa Pemilik' }}</span>
                                @endif
                            </td>
                            <td class="p-4 text-gray-700 font-medium">
                                {{ $jumlahKendaraan > 1 ? 'Layanan Kolektif' : $firstTrx->jenis_layanan }}
                            </td>
                            <td class="p-4 text-center">
                                <span class="px-3 py-1 text-xs font-semibold rounded-full border {{ $statusColor }}">
                                    {{ $statusGroup }}
                                </span>
                            </td>
                            <td class="p-4 text-right font-semibold text-gray-800">
                                Rp {{ number_format($totalBiayaGroup, 0, ',', '.') }}
                            </td>
                            <td class="p-4">
                                <div class="flex justify-center items-center gap-1.5">

                                    <!-- Tombol Lihat Rincian (Khusus Kolektif) -->
                                    @if($jumlahKendaraan > 1)
                                    <button type="button" onclick="toggleRincian('{{ $baseInvoice }}')" title="Lihat Rincian" class="w-8 h-8 flex items-center justify-center rounded-lg bg-blue-50 hover:bg-blue-100 text-blue-600 transition-colors">
                                        <i class="fa-solid fa-list-ul text-xs"></i>
                                    </button>
                                    @endif

                                    <button type="button" class="btn-preview w-8 h-8 flex items-center justify-center rounded-lg bg-gray-100 hover:bg-gray-200 text-gray-600 transition-colors" title="Preview Invoice"
                                        data-preview="{{ json_encode($previewData) }}">
                                        <i class="fa-solid fa-eye text-xs"></i>
                                    </button>

                                    <a href="{{ route('transactions.print', $firstTrx->id) }}" target="_blank" title="Cetak Tanda Terima" class="w-8 h-8 flex items-center justify-center rounded-lg bg-gray-100 hover:bg-gray-200 text-gray-600 transition-colors">
                                        <i class="fa-solid fa-print text-xs"></i>
                                    </a>

                                    <!-- Edit & Delete Induk (Hanya tampil jika single transaksi agar aman) -->
                                    @if($jumlahKendaraan == 1)
                                        <a href="{{ route('transactions.edit', $firstTrx->id) }}" title="Edit" class="w-8 h-8 flex items-center justify-center rounded-lg bg-blue-50 hover:bg-blue-100 text-blue-600 transition-colors">
                                            <i class="fa-solid fa-pen-to-square text-xs"></i>
                                        </a>

                                        @if(Auth::user()->role === 'super_admin')
                                            <form action="{{ route('transactions.destroy', $firstTrx->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus transaksi ini?');" class="inline-flex">
                                                @csrf @method('DELETE')
                                                <button type="submit" title="Hapus" class="w-8 h-8 flex items-center justify-center rounded-lg bg-red-50 hover:bg-red-100 text-red-600 transition-colors">
                                                    <i class="fa-solid fa-trash-can text-xs"></i>
                                                </button>
                                            </form>
                                        @endif
                                    @endif
                                </div>
                            </td>
                        </tr>

                        <!-- ========================================================== -->
                        <!-- 2. BARIS ANAK (Hanya muncul jika Kolektif & Tombol Diklik) -->
                        <!-- ========================================================== -->
                        @if($jumlahKendaraan > 1)
                            @foreach($trxs as $trx)
                            <tr class="hidden anak-row-{{ $baseInvoice }} bg-blue-50/40 text-xs border-l-4 border-blue-400">
                                <td class="p-3 text-center">
                                    <i class="fa-solid fa-arrow-turn-up fa-rotate-90 text-blue-300"></i>
                                </td>
                                <td class="p-3 text-gray-500 pl-4">{{ $trx->invoice_no }}</td>
                                <td class="p-3 font-semibold text-gray-700">{{ $trx->vehicle->nopol ?? '-' }}</td>
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
                                    <span class="text-[10px] font-semibold px-2 py-0.5 rounded-full {{ $badgeColor }}">
                                        {{ $trx->status_proses }}
                                    </span>
                                </td>
                                <td class="p-3 text-right font-semibold text-gray-700">Rp {{ number_format($trx->total_biaya, 0, ',', '.') }}</td>
                                <td class="p-3">
                                    <div class="flex justify-center gap-1.5">
                                        <!-- Edit & Delete per Kendaraan -->
                                        <a href="{{ route('transactions.edit', $trx->id) }}" title="Edit Kendaraan Ini" class="w-7 h-7 flex items-center justify-center rounded-lg bg-blue-50 hover:bg-blue-100 text-blue-600 transition-colors">
                                            <i class="fa-solid fa-pen-to-square text-xs"></i>
                                        </a>

                                        @if(Auth::user()->role === 'super_admin')
                                        <form action="{{ route('transactions.destroy', $trx->id) }}" method="POST" onsubmit="return confirm('Hapus kendaraan ini dari invoice kolektif?');" class="inline-flex">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="w-7 h-7 flex items-center justify-center rounded-lg bg-red-50 hover:bg-red-100 text-red-600 transition-colors">
                                                <i class="fa-solid fa-trash-can text-xs"></i>
                                            </button>
                                        </form>
                                        @endif
                                    </div>
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
            <div class="px-5 py-4 border-t border-gray-300 flex flex-col sm:flex-row justify-between items-center gap-3">
                <div class="text-sm text-gray-500">
                    Menampilkan <span class="font-semibold text-gray-700">{{ $transactions->firstItem() }}</span> - <span class="font-semibold text-gray-700">{{ $transactions->lastItem() }}</span> dari <span class="font-semibold text-gray-700">{{ $transactions->total() }}</span> data
                </div>
                <div class="flex items-center gap-1">
                    @if($transactions->onFirstPage())
                        <span class="px-3 py-2 border border-gray-200 rounded-lg text-gray-300 bg-gray-50">
                            <i class="fa-solid fa-chevron-left"></i>
                        </span>
                    @else
                        <a href="{{ $transactions->withQueryString()->previousPageUrl() }}" class="px-3 py-2 border border-gray-200 rounded-lg text-gray-600 bg-white hover:bg-blue-50 hover:text-blue-700 transition-colors">
                            <i class="fa-solid fa-chevron-left"></i>
                        </a>
                    @endif

                    @foreach($transactions->getUrlRange(1, $transactions->lastPage()) as $page => $url)
                        @if($page == $transactions->currentPage())
                            <span class="px-3 py-2 rounded-lg bg-blue-600 text-white font-semibold">{{ $page }}</span>
                        @else
                            <a href="{{ $transactions->withQueryString()->url($page) }}" class="px-3 py-2 border border-gray-200 rounded-lg text-gray-600 bg-white hover:bg-blue-50 hover:text-blue-700 transition-colors">{{ $page }}</a>
                        @endif
                    @endforeach

                    @if($transactions->hasMorePages())
                        <a href="{{ $transactions->withQueryString()->nextPageUrl() }}" class="px-3 py-2 border border-gray-200 rounded-lg text-gray-600 bg-white hover:bg-blue-50 hover:text-blue-700 transition-colors">
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
        <!-- ==================================================== -->
        <!-- MODAL PREVIEW INVOICE (DARI INDEX)                   -->
        <!-- ==================================================== -->
        <div id="indexPreviewModal" class="hidden fixed inset-0 z-50 bg-gray-900/60 backdrop-blur-sm overflow-y-auto flex items-center justify-center p-4 transition-opacity">
            <div class="bg-white rounded-2xl shadow-2xl w-full max-w-5xl overflow-hidden flex flex-col max-h-[90vh] scale-100">
                
                <!-- Modal Header -->
                <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center bg-gray-50">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center text-lg">
                            <i class="fa-solid fa-magnifying-glass-dollar"></i>
                        </div>
                        <div>
                            <h3 class="font-semibold text-gray-800 text-lg">Preview Tagihan <span id="prevInvNo" class="text-blue-600"></span></h3>
                            <p class="text-xs text-gray-500 font-medium" id="prevInvClient"></p>
                        </div>
                    </div>
                    <button type="button" id="btnClosePreview" class="text-gray-400 hover:text-red-500 hover:bg-red-50 w-8 h-8 rounded-full flex items-center justify-center transition-colors">
                        <i class="fa-solid fa-xmark text-xl"></i>
                    </button>
                </div>

                <!-- Modal Body (Isi Tabel) -->
                <div class="p-6 overflow-y-auto bg-white flex-1">
                    <div class="flex justify-between items-center mb-3">
                        <span class="text-sm font-semibold text-gray-700">Rincian Kendaraan:</span>
                        <span class="text-xs text-gray-500 font-medium bg-gray-100 px-3 py-1 rounded-full" id="prevInvDate"></span>
                    </div>
                    
                    <table class="w-full text-sm text-left border border-gray-300 rounded-lg overflow-hidden">
                        <thead class="bg-blue-50 text-blue-800 border-b border-blue-100">
                            <tr>
                                <th class="p-3 font-semibold">No. Polisi</th>
                                <th class="p-3 font-semibold">Kendaraan</th>
                                <th class="p-3 font-semibold">Layanan</th>
                                <th class="p-3 font-semibold text-right">Pajak</th>
                                <th class="p-3 font-semibold text-right">Jasa</th>
                                <th class="p-3 font-semibold text-right">Biaya Lain</th>
                                <th class="p-3 font-semibold text-right">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody id="prevTableBody" class="divide-y divide-gray-200">
                            <!-- Baris diisi oleh JS -->
                        </tbody>
                        <tfoot class="bg-gray-50 border-t border-gray-300">
                            <tr>
                                <th colspan="6" class="p-4 text-right font-semibold text-gray-700 uppercase tracking-wider text-xs">Grand Total Keseluruhan:</th>
                                <th class="p-4 text-right text-xl font-semibold text-blue-700" id="prevGrandTotal">Rp 0</th>
                            </tr>
                        </tfoot>
                    </table>

                    <!-- Area Terbilang -->
                    <div class="mt-4 mb-2 text-sm">
                        <div class="flex items-center">
                            <div class="w-1/4 text-gray-700 uppercase font-semibold text-xs tracking-wider">TERBILANG :</div>
                            <div class="font-semibold italic flex-1 capitalize bg-blue-50 border border-blue-100 text-blue-800 px-4 py-2 rounded text-xs" id="prev_terbilang">
                                <!-- Teks akan diisi oleh JS -->
                            </div>
                        </div>
                    </div>

                    <!-- Rincian Biaya Lainnya (Tampil Seperti Invoice) -->
                    <div class="mt-6 flex justify-between items-start border-t border-gray-200 pt-5">
                        <div class="w-1/2">
                            <p class="font-semibold text-gray-700 uppercase text-xs tracking-wider">Rincian Biaya Lainnya :</p>
                            <p class="text-[11px] text-gray-500 italic mt-1">Akumulasi total dari seluruh kendaraan di atas.</p>
                        </div>
                        <div class="w-1/2 flex justify-end">
                            <table class="w-[90%] border-collapse border border-gray-300 text-xs text-left bg-gray-50/30 rounded-lg overflow-hidden">
                                <tbody class="divide-y divide-gray-200">
                                    <tr>
                                        <td class="p-2 text-gray-700">Loket Pendaftaran</td>
                                        <td class="p-2 w-8">Rp</td>
                                        <td class="p-2 text-right font-medium" id="prev_pendaftaran">-</td>
                                    </tr>
                                    <tr>
                                        <td class="p-2 text-gray-700">Loket Cek Fisik</td>
                                        <td class="p-2">Rp</td>
                                        <td class="p-2 text-right font-medium" id="prev_cek_fisik">-</td>
                                    </tr>
                                    <tr>
                                        <td class="p-2 text-gray-700">Acc Tidak Hadir STNK</td>
                                        <td class="p-2">Rp</td>
                                        <td class="p-2 text-right font-medium" id="prev_acc_tidak_hadir">-</td>
                                    </tr>
                                    <tr>
                                        <td class="p-2 text-gray-700">Acc Domisili (Beda Alamat)</td>
                                        <td class="p-2">Rp</td>
                                        <td class="p-2 text-right font-medium" id="prev_acc_domisili">-</td>
                                    </tr>
                                    <tr>
                                        <td class="p-2 text-gray-700">Loket Penetapan</td>
                                        <td class="p-2">Rp</td>
                                        <td class="p-2 text-right font-medium" id="prev_penetapan">-</td>
                                    </tr>
                                    <tr>
                                        <td class="p-2 text-gray-700">Loket Pengesahan Pertama</td>
                                        <td class="p-2">Rp</td>
                                        <td class="p-2 text-right font-medium" id="prev_pengesahan_1">-</td>
                                    </tr>
                                    <tr>
                                        <td class="p-2 text-gray-700">Loket Pengesahan Kedua</td>
                                        <td class="p-2">Rp</td>
                                        <td class="p-2 text-right font-medium" id="prev_pengesahan_2">-</td>
                                    </tr>
                                    <tr>
                                        <td class="p-2 text-gray-700">Bea Materai</td>
                                        <td class="p-2">Rp</td>
                                        <td class="p-2 text-right font-medium" id="prev_materai">-</td>
                                    </tr>
                                    <tr class="font-semibold bg-blue-50 border-t border-blue-100">
                                        <td class="p-2 text-right text-blue-800 uppercase text-[10px]">Total Biaya Lain</td>
                                        <td class="p-2 text-blue-800">Rp</td>
                                        <td class="p-2 text-right text-blue-800" id="prev_total_lain">0</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Modal Footer -->
                <div class="px-6 py-4 border-t border-gray-200 bg-gray-50 flex justify-end gap-3">
                    <button type="button" id="btnTutupPreview" class="px-6 py-2.5 bg-gray-700 hover:bg-gray-800 text-white font-semibold rounded-lg shadow-sm transition-colors">
                        Tutup Preview
                    </button>
                </div>
            </div>
        </div>
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

        // LOGIKA POP-UP PREVIEW INDEX
        // =======================================================
        document.addEventListener('DOMContentLoaded', function() {
            const formatRupiah = (angka) => new Intl.NumberFormat('id-ID').format(angka || 0);
            
            const previewModal = document.getElementById('indexPreviewModal');
            const prevTableBody = document.getElementById('prevTableBody');
            const btnCloseBtns = [document.getElementById('btnClosePreview'), document.getElementById('btnTutupPreview')];

            // 1. Tangkap semua klik pada tombol Preview
            document.querySelectorAll('.btn-preview').forEach(button => {
                button.addEventListener('click', function() {
                    // Ambil data JSON dari atribut tombol
                    const data = JSON.parse(this.getAttribute('data-preview'));
                    
                    // Isi Header Modal
                    document.getElementById('prevInvNo').innerText = data.invoice_no;
                    document.getElementById('prevInvClient').innerHTML = `<i class="fa-regular fa-user mr-1"></i> ${data.client_name}`;
                    document.getElementById('prevInvDate').innerHTML = `<i class="fa-regular fa-calendar mr-1"></i> ${data.date}`;
                    
                    // Looping Kendaraan ke dalam Tabel
                    let htmlRows = '';
                    data.items.forEach(item => {
                        htmlRows += `
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="p-3 font-semibold text-gray-800 uppercase tracking-wide">${item.nopol}</td>
                                <td class="p-3 text-xs text-gray-600">${item.merk}</td>
                                <td class="p-3 text-gray-600">${item.layanan}</td>
                                <td class="p-3 text-right text-gray-600">Rp ${formatRupiah(item.pajak)}</td>
                                <td class="p-3 text-right text-gray-600">Rp ${formatRupiah(item.jasa)}</td>
                                <td class="p-3 text-right text-gray-600">Rp ${formatRupiah(item.lain)}</td>
                                <td class="p-3 text-right font-semibold text-blue-600">Rp ${formatRupiah(item.total)}</td>
                            </tr>
                        `;
                    });
                    
                    // Tampilkan isi tabel & Grand Total
                    prevTableBody.innerHTML = htmlRows;
                    document.getElementById('prevGrandTotal').innerText = 'Rp ' + formatRupiah(data.grand_total);
                    // KALIMAT TERBILANG
                    document.getElementById('prev_terbilang').innerText = '** ' + data.terbilang + ' **';
                    // TAMBAHAN BARU: Tampilkan Rincian Biaya Lainnya
                    // =========================================================
                    const rl = data.rincian_lain;
                    document.getElementById('prev_pendaftaran').innerText = rl.pendaftaran > 0 ? formatRupiah(rl.pendaftaran) : '-';
                    document.getElementById('prev_cek_fisik').innerText = rl.cek_fisik > 0 ? formatRupiah(rl.cek_fisik) : '-';
                    document.getElementById('prev_acc_tidak_hadir').innerText = rl.acc_tidak_hadir > 0 ? formatRupiah(rl.acc_tidak_hadir) : '-';
                    document.getElementById('prev_acc_domisili').innerText = rl.acc_domisili > 0 ? formatRupiah(rl.acc_domisili) : '-';
                    document.getElementById('prev_penetapan').innerText = rl.penetapan > 0 ? formatRupiah(rl.penetapan) : '-';
                    document.getElementById('prev_pengesahan_1').innerText = rl.pengesahan_1 > 0 ? formatRupiah(rl.pengesahan_1) : '-';
                    document.getElementById('prev_pengesahan_2').innerText = rl.pengesahan_2 > 0 ? formatRupiah(rl.pengesahan_2) : '-';
                    document.getElementById('prev_materai').innerText = rl.materai > 0 ? formatRupiah(rl.materai) : '-';
                    
                    document.getElementById('prev_total_lain').innerText = formatRupiah(data.grand_total_lain);
                    // Tampilkan Modal ke Layar
                    previewModal.classList.remove('hidden');
                });
            });

            // 2. Fungsi Menutup Modal
            const closeModal = () => previewModal.classList.add('hidden');
            
            btnCloseBtns.forEach(btn => {
                if(btn) btn.addEventListener('click', closeModal);
            });

            // 3. Menutup Modal dengan klik di luar kotak (backdrop)
            previewModal.addEventListener('click', function(e) {
                if (e.target === previewModal) {
                    closeModal();
                }
            });
        });

        
    </script>

@endsection