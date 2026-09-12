<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice - {{ $baseInvoice }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @media print {
            @page { margin: 1cm; size: A4 portrait; }
            body { -webkit-print-color-adjust: exact; print-color-adjust: exact; background-color: white !important; }
            .print\:hidden { display: none !important; }
            .bg-gray-100 { background-color: #f3f4f6 !important; }
        }
    </style>
</head>
<body class="bg-gray-200 text-gray-800 font-sans p-8 print:p-0 print:bg-white relative">

    <?php
        // FUNGSI UNTUK MENGUBAH ANGKA MENJADI TEKS (TERBILANG)
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
        function terbilang($nilai) {
            if($nilai == 0) return "Nol Rupiah";
            if($nilai < 0) { $hasil = "Minus ". trim(penyebut($nilai)); } 
            else { $hasil = trim(penyebut($nilai)); }
            return $hasil . " Rupiah";
        }
    ?>

    @php
        // DATA SETUP UNTUK INVOICE KOLEKTIF
        $firstTrx = $transactions->first();
        $client = $firstTrx->vehicle->client ?? null;
        
        // Kalkulasi Grand Total Keseluruhan
        $grandTotalPajak = $transactions->sum('biaya_pajak');
        $grandTotalJasa = $transactions->sum('biaya_jasa');
        $grandTotalLain = $transactions->sum('biaya_lain');
        $grandTotalBiaya = $transactions->sum('total_biaya');

        // Kalkulasi Sub-Total Rincian Biaya Lainnya
        $sumPendaftaran = $transactions->sum('loket_pendaftaran');
        $sumCekFisik = $transactions->sum('loket_cek_fisik');
        $sumAccTidakHadir = $transactions->sum('acc_tidak_hadir');
        $sumAccDomisili = $transactions->sum('acc_domisili');
        $sumPenetapan = $transactions->sum('loket_penetapan');
        $sumPengesahan1 = $transactions->sum('loket_pengesahan_1');
        $sumPengesahan2 = $transactions->sum('loket_pengesahan_2');
        $sumMaterai = $transactions->sum('bea_materai');
    @endphp

    <!-- Tombol Navigasi (Hilang saat diprint) -->
    <div class="max-w-5xl mx-auto mb-6 print:hidden flex justify-between items-center bg-white p-4 rounded-lg shadow">
        <a href="{{ route('transactions.index') }}" class="px-5 py-2 bg-gray-500 hover:bg-gray-600 text-white font-bold rounded transition-colors">
            &larr; Kembali
        </a>
        <div class="flex gap-3">
            <button onclick="window.print()" class="px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded shadow transition-colors flex items-center gap-2">
                <i class="fa-solid fa-print"></i> Print / Cetak PDF
            </button>
        </div>
    </div>

    <!-- KERTAS INVOICE -->
    <div class="max-w-5xl mx-auto bg-white border border-gray-200 p-10 min-h-[29.7cm] shadow-lg print:shadow-none print:border-none relative">
        
        <!-- HEADER KOP SURAT -->
        <div class="flex justify-between items-start mb-8">
            <div class="w-1/2">
                <div class="flex items-center gap-2 mb-2">
                    <div class="w-12 h-12 bg-blue-900 flex items-center justify-center text-white font-black text-2xl rounded-sm shadow-sm">
                        BJ
                    </div>
                    <div>
                        <h1 class="text-2xl font-black text-gray-900 tracking-tight leading-none">BIRO JASA STNK</h1>
                        <p class="text-[10px] text-gray-500 italic mt-0.5">Committed to Service, Excellence in Every Process</p>
                    </div>
                </div>
                <p class="text-xs text-gray-700 mt-3">Alamat: Jl. Contoh Kemerdekaan No. 45, Kota Anda, 12345</p>
                <p class="text-xs text-gray-700">No.Telp : +62 812-3456-7890</p>
            </div>
            
            <div class="w-1/2 text-right">
                <h2 class="text-4xl font-black text-gray-900 tracking-widest mb-4">INVOICE</h2>
                <table class="w-full text-xs text-left ml-auto" style="max-width: 250px;">
                    <tr>
                        <td class="py-1 text-gray-600">No Invoice</td>
                        <td class="py-1 font-bold">: {{ $baseInvoice }}</td>
                    </tr>
                    <tr>
                        <td class="py-1 text-gray-600">Tgl. Pembuatan</td>
                        <td class="py-1 font-bold">: {{ \Carbon\Carbon::parse($firstTrx->created_at)->format('d/m/Y') }}</td>
                    </tr>
                    <tr>
                        <td class="py-1 text-gray-600">Termin (hari)</td>
                        <td class="py-1 font-bold">: 14</td>
                    </tr>
                </table>
            </div>
        </div>

        <!-- INFO KEPADA YTH -->
        <div class="mb-6">
            <p class="text-sm text-gray-700 mb-1">Kepada Yth :</p>
            <h3 class="font-bold text-lg text-gray-900 uppercase">{{ $client->nama_lengkap ?? 'KLIEN UMUM' }}</h3>
            <p class="text-xs text-gray-700 mt-1">{{ $client->alamat ?? '-' }}</p>
            <p class="text-xs text-gray-700 mt-1">No. WhatsApp / Telp: {{ $client->no_whatsapp ?? '-' }}</p>
        </div>

        <p class="text-sm text-gray-800 mb-2">
            Dengan ini, kami menyampaikan pengajuan atas <strong>Layanan Biro Jasa Kolektif</strong> <span class="float-right underline text-xs">Kendaraan Terlampir:</span>
        </p>

        <!-- TABEL UTAMA (RINCIAN KENDARAAN) -->
        <table class="w-full text-sm border-collapse border border-gray-900 mb-6">
            <thead>
                <tr class="bg-gray-100">
                    <th class="border border-gray-900 py-2 px-3 text-center w-8">No</th>
                    <th class="border border-gray-900 py-2 px-3 text-center">No Polisi</th>
                    <th class="border border-gray-900 py-2 px-3 text-center">Merk Type</th>
                    <th class="border border-gray-900 py-2 px-3 text-center">Layanan</th>
                    <th class="border border-gray-900 py-2 px-3 text-center">Pajak</th>
                    <th class="border border-gray-900 py-2 px-3 text-center">Jasa</th>
                    <th class="border border-gray-900 py-2 px-3 text-center">Biaya Lain</th>
                    <th class="border border-gray-900 py-2 px-3 text-center w-28">Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach($transactions as $trx)
                <tr>
                    <td class="border border-gray-900 py-2 px-3 text-center">{{ $loop->iteration }}</td>
                    <td class="border border-gray-900 py-2 px-3 text-center text-xs font-bold">{{ $trx->vehicle->nopol }}</td>
                    <td class="border border-gray-900 py-2 px-3 text-center text-xs">{{ $trx->vehicle->merk }} {{ $trx->vehicle->tipe }}</td>
                    <td class="border border-gray-900 py-2 px-3 text-center text-xs">{{ $trx->jenis_layanan }}</td>
                    <td class="border border-gray-900 py-2 px-3 text-right text-xs">{{ number_format($trx->biaya_pajak, 0, ',', '.') }}</td>
                    <td class="border border-gray-900 py-2 px-3 text-right text-xs">{{ number_format($trx->biaya_jasa, 0, ',', '.') }}</td>
                    <td class="border border-gray-900 py-2 px-3 text-right text-xs">{{ number_format($trx->biaya_lain, 0, ',', '.') }}</td>
                    <td class="border border-gray-900 py-2 px-3 text-right font-bold justify-between text-xs flex">
                        <span>Rp</span> <span>{{ number_format($trx->total_biaya, 0, ',', '.') }}</span>
                    </td>
                </tr>
                @endforeach
                
                <!-- Baris Total Keseluruhan -->
                <tr class="bg-gray-100 font-bold">
                    <td colspan="4" class="border border-gray-900 py-3 px-3 text-right tracking-widest text-sm uppercase">Grand Total Keseluruhan</td>
                    <td class="border border-gray-900 py-3 px-3 text-right text-xs">{{ number_format($grandTotalPajak, 0, ',', '.') }}</td>
                    <td class="border border-gray-900 py-3 px-3 text-right text-xs">{{ number_format($grandTotalJasa, 0, ',', '.') }}</td>
                    <td class="border border-gray-900 py-3 px-3 text-right text-xs">{{ number_format($grandTotalLain, 0, ',', '.') }}</td>
                    <td class="border border-gray-900 py-3 px-3 text-right flex justify-between text-base">
                        <span>Rp</span> <span>{{ number_format($grandTotalBiaya, 0, ',', '.') }}</span>
                    </td>
                </tr>
            </tbody>
        </table>

        <!-- TERBILANG & CATATAN -->
        <div class="mb-10 text-sm">
            <div class="flex mb-2">
                <div class="w-32 text-gray-700 uppercase font-bold">TERBILANG:</div>
                <div class="font-bold italic flex-1 capitalize bg-gray-100 px-3 py-1 rounded">** {{ terbilang($grandTotalBiaya) }} **</div>
            </div>
        </div>

        <!-- TABEL RINCIAN BIAYA LAIN (GABUNGAN SEMUA KENDARAAN) -->
        <div class="flex justify-between items-start mb-16 text-sm">
            <div class="w-1/2">
                <p class="font-bold text-gray-700 uppercase">Rincian Biaya Lainnya (Total Kolektif):</p>
                <p class="text-xs text-gray-500 italic mt-1">Rincian ini merupakan akumulasi dari seluruh kendaraan di atas.</p>
            </div>
            <div class="w-1/2 flex justify-end">
                <table class="w-[85%] border-collapse border border-gray-900 text-xs">
                    <tbody>
                        <tr>
                            <td class="border border-gray-900 px-3 py-1.5 text-gray-700">Loket Pendaftaran</td>
                            <td class="border border-gray-900 px-3 py-1.5 w-8">Rp</td>
                            <td class="border border-gray-900 px-3 py-1.5 text-right">{{ $sumPendaftaran > 0 ? number_format($sumPendaftaran, 0, ',', '.') : '-' }}</td>
                        </tr>
                        <tr>
                            <td class="border border-gray-900 px-3 py-1.5 text-gray-700">Loket Cek Fisik</td>
                            <td class="border border-gray-900 px-3 py-1.5">Rp</td>
                            <td class="border border-gray-900 px-3 py-1.5 text-right">{{ $sumCekFisik > 0 ? number_format($sumCekFisik, 0, ',', '.') : '-' }}</td>
                        </tr>
                        <tr>
                            <td class="border border-gray-900 px-3 py-1.5 text-gray-700">Acc tidak hadir STNK</td>
                            <td class="border border-gray-900 px-3 py-1.5">Rp</td>
                            <td class="border border-gray-900 px-3 py-1.5 text-right">{{ $sumAccTidakHadir > 0 ? number_format($sumAccTidakHadir, 0, ',', '.') : '-' }}</td>
                        </tr>
                        <tr>
                            <td class="border border-gray-900 px-3 py-1.5 text-gray-700">Acc Domisili (Beda Alamat)</td>
                            <td class="border border-gray-900 px-3 py-1.5">Rp</td>
                            <td class="border border-gray-900 px-3 py-1.5 text-right">{{ $sumAccDomisili > 0 ? number_format($sumAccDomisili, 0, ',', '.') : '-' }}</td>
                        </tr>
                        <tr>
                            <td class="border border-gray-900 px-3 py-1.5 text-gray-700">Loket Penetapan</td>
                            <td class="border border-gray-900 px-3 py-1.5">Rp</td>
                            <td class="border border-gray-900 px-3 py-1.5 text-right">{{ $sumPenetapan > 0 ? number_format($sumPenetapan, 0, ',', '.') : '-' }}</td>
                        </tr>
                        <tr>
                            <td class="border border-gray-900 px-3 py-1.5 text-gray-700">Loket Pengesahan Pertama</td>
                            <td class="border border-gray-900 px-3 py-1.5">Rp</td>
                            <td class="border border-gray-900 px-3 py-1.5 text-right">{{ $sumPengesahan1 > 0 ? number_format($sumPengesahan1, 0, ',', '.') : '-' }}</td>
                        </tr>
                        <tr>
                            <td class="border border-gray-900 px-3 py-1.5 text-gray-700">Loket Pengesahan Kedua</td>
                            <td class="border border-gray-900 px-3 py-1.5">Rp</td>
                            <td class="border border-gray-900 px-3 py-1.5 text-right">{{ $sumPengesahan2 > 0 ? number_format($sumPengesahan2, 0, ',', '.') : '-' }}</td>
                        </tr>
                        <tr>
                            <td class="border border-gray-900 px-3 py-1.5 text-gray-700">Bea Materai</td>
                            <td class="border border-gray-900 px-3 py-1.5">Rp</td>
                            <td class="border border-gray-900 px-3 py-1.5 text-right">{{ $sumMaterai > 0 ? number_format($sumMaterai, 0, ',', '.') : '-' }}</td>
                        </tr>
                        <tr class="font-bold bg-gray-100">
                            <td class="border border-gray-900 px-3 py-2 text-right">Total Biaya Lain</td>
                            <td class="border border-gray-900 px-3 py-2">Rp</td>
                            <td class="border border-gray-900 px-3 py-2 text-right">{{ number_format($grandTotalLain, 0, ',', '.') }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- FOOTER & TANDA TANGAN -->
        <div class="flex justify-between items-end mt-10 pt-8 border-t border-dashed border-gray-300">
            <!-- Tanda Tangan Kiri -->
            <div class="text-left w-64">
                <p class="text-sm text-gray-800 mb-20">{{ \Carbon\Carbon::now()->format('d/m/Y') }}<br>Dibuat Oleh,</p>
                <p class="font-bold text-gray-900 uppercase">ADMIN BIRO JASA</p>
            </div>
            
            <!-- Rekening Bank Kanan -->
            <div class="text-right">
                <p class="text-xs text-gray-500 mb-1">Pembayaran tagihan mohon ditransfer pada rekening tertera:</p>
                <p class="font-black text-gray-900 text-lg tracking-wider">BANK BCA No.Rek 1234567890</p>
                <p class="text-sm font-bold text-gray-700">a/n NAMA PEMILIK BIRO JASA</p>
            </div>
        </div>

    </div>
</body>
</html>