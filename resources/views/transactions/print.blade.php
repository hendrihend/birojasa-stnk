<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice - {{ $transaction->vehicle->nopol }}</title>
    <!-- Gunakan Tailwind CDN khusus untuk halaman cetak agar ringan -->
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @media print {
            @page { margin: 1cm; size: A4 portrait; }
            body { -webkit-print-color-adjust: exact; print-color-adjust: exact; background-color: white !important; }
            .print\:hidden { display: none !important; }
            /* Memaksa background tabel tetap muncul saat di-print */
            .bg-gray-100 { background-color: #f3f4f6 !important; }
        }
    </style>
</head>
<body class="bg-gray-200 text-gray-800 font-sans p-8 print:p-0 print:bg-white">

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
            if($nilai<0) { $hasil = "Minus ". trim(penyebut($nilai)); } 
            else { $hasil = trim(penyebut($nilai)); }
            return $hasil . " Rupiah";
        }
    ?>

    <!-- Tombol Navigasi (Hilang saat diprint) -->
    <div class="max-w-4xl mx-auto mb-6 print:hidden flex justify-between items-center bg-white p-4 rounded-lg shadow">
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
    <div class="max-w-4xl mx-auto bg-white border border-gray-200 p-10 min-h-[29.7cm] shadow-lg print:shadow-none print:border-none relative">
        
        <!-- HEADER KOP SURAT -->
        <div class="flex justify-between items-start mb-8">
            <div class="w-1/2">
                <!-- Ganti dengan tag <img> jika sudah punya logo beneran -->
                <div class="flex items-center gap-2 mb-2">
                    <div class="w-10 h-10 bg-blue-900 flex items-center justify-center text-white font-bold text-xl rounded-sm">
                        BJ
                    </div>
                    <div>
                        <h1 class="text-2xl font-black text-gray-900 tracking-tight leading-none">BIRO JASA STNK</h1>
                        <p class="text-[10px] text-gray-500 italic">Committed to Service, Excellence in Every Process</p>
                    </div>
                </div>
                <p class="text-xs text-gray-700 mt-2">Alamat: Jl. Contoh Kemerdekaan No. 45, Kota Anda, 12345</p>
                <p class="text-xs text-gray-700">No.Telp : +62 812-3456-7890</p>
            </div>
            
            <div class="w-1/2 text-right">
                <h2 class="text-4xl font-black text-gray-900 tracking-widest mb-4">INVOICE</h2>
                <table class="w-full text-xs text-left ml-auto" style="max-width: 250px;">
                    <tr>
                        <td class="py-1 text-gray-600">No Invoice</td>
                        <td class="py-1 font-bold">: {{ $transaction->invoice_no ?? ('TRX-'.str_pad($transaction->id, 5, '0', STR_PAD_LEFT)) }}</td>
                    </tr>
                    <tr>
                        <td class="py-1 text-gray-600">Tgl. Pembuatan</td>
                        <td class="py-1 font-bold">: {{ \Carbon\Carbon::parse($transaction->created_at)->format('d/m/Y') }}</td>
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
            <h3 class="font-bold text-lg text-gray-900 uppercase">{{ $transaction->vehicle->client->nama_lengkap ?? 'KLIEN UMUM' }}</h3>
            <p class="text-xs text-gray-700 mt-1">{{ $transaction->vehicle->client->alamat ?? '-' }}</p>
            <p class="text-xs text-gray-700 mt-1">No. WhatsApp / Telp: {{ $transaction->vehicle->client->no_whatsapp ?? '-' }}</p>
        </div>

        <p class="text-sm text-gray-800 mb-2">
            Dengan ini, kami menyampaikan pengajuan atas <strong>{{ $transaction->jenis_layanan }}</strong> <span class="float-right underline text-xs">Kendaraan Terlampir:</span>
        </p>

        <!-- TABEL RINCIAN BIAYA -->
        <table class="w-full text-sm border-collapse border border-gray-900 mb-4">
            <thead>
                <tr class="bg-gray-100">
                    <th class="border border-gray-900 py-2 px-3 text-center w-10">No</th>
                    <th class="border border-gray-900 py-2 px-3 text-center">No Polisi</th>
                    <th class="border border-gray-900 py-2 px-3 text-center">Merk Type</th>
                    <th class="border border-gray-900 py-2 px-3 text-center">Pajak</th>
                    <th class="border border-gray-900 py-2 px-3 text-center">Jasa</th>
                    <th class="border border-gray-900 py-2 px-3 text-center">Biaya Lain</th>
                    <th class="border border-gray-900 py-2 px-3 text-center w-32">Total</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td class="border border-gray-900 py-3 px-3 text-center">1</td>
                    <td class="border border-gray-900 py-3 px-3 text-center font-bold">{{ $transaction->vehicle->nopol }}</td>
                    <td class="border border-gray-900 py-3 px-3 text-center text-xs">{{ $transaction->vehicle->merk }} {{ $transaction->vehicle->tipe }}</td>
                    <td class="border border-gray-900 py-3 px-3 text-right">{{ number_format($transaction->biaya_pajak, 0, ',', '.') }}</td>
                    <td class="border border-gray-900 py-3 px-3 text-right">{{ number_format($transaction->biaya_jasa, 0, ',', '.') }}</td>
                    <td class="border border-gray-900 py-3 px-3 text-right">{{ number_format($transaction->biaya_lain, 0, ',', '.') }}</td>
                    <td class="border border-gray-900 py-3 px-3 text-right font-bold flex justify-between">
                        <span>Rp</span> <span>{{ number_format($transaction->total_biaya, 0, ',', '.') }}</span>
                    </td>
                </tr>
                <!-- Baris Total -->
                <tr class="bg-gray-100 font-bold">
                    <td colspan="6" class="border border-gray-900 py-3 px-3 text-center tracking-widest">TOTAL</td>
                    <td class="border border-gray-900 py-3 px-3 text-right flex justify-between text-base">
                        <span>Rp</span> <span>{{ number_format($transaction->total_biaya, 0, ',', '.') }}</span>
                    </td>
                </tr>
            </tbody>
        </table>

        <!-- TERBILANG & CATATAN -->
        <div class="mb-10 text-sm">
            <div class="flex mb-2">
                <div class="w-32 text-gray-700">TERBILANG:</div>
                <div class="font-bold italic flex-1 capitalize">{{ terbilang($transaction->total_biaya) }}</div>
            </div>
            @if($transaction->catatan)
            <div class="flex">
                <div class="w-32 text-gray-700">Catatan:</div>
                <div class="flex-1 bg-gray-100 px-3 py-1 text-xs inline-block">{{ $transaction->catatan }}</div>
            </div>
            @endif
        </div>

        <!-- FOOTER & TANDA TANGAN -->
        <div class="flex justify-between items-end mt-20 pt-8 absolute bottom-10 left-10 right-10">
            <!-- Tanda Tangan Kiri -->
            <div class="text-left w-64">
                <p class="text-sm text-gray-800 mb-16">{{ \Carbon\Carbon::now()->format('d/m/Y') }}<br>Dibuat Oleh,</p>
                <!-- Space untuk stempel/ttd asli -->
                <p class="font-bold text-gray-900 underline uppercase mt-20">ADMIN BIRO JASA</p>
            </div>
            
            <!-- Rekening Bank Kanan -->
            <div class="text-right">
                <p class="text-xs text-gray-500 mb-1">Pembayaran tagihan mohon ditransfer pada rekening tertera:</p>
                <p class="font-black text-gray-900 text-lg tracking-wider">BANK BCA No.Rek 1234567890</p>
                <p class="text-sm font-bold text-gray-700">a/n NAMA PEMILIK BIRO JASA</p>
            </div>
        </div>

    </div>
    <!-- Script untuk memicu dialog print otomatis saat halaman dimuat -->
    <script>
        window.onload = function() {
            setTimeout(function() {
                window.print();
            }, 500); // Jeda setengah detik agar CSS Tailwind termuat sempurna
        };
    </script>
</body>
</html>
