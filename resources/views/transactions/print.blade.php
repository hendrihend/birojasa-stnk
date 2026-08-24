<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice - {{ $transaction->vehicle->nopol }}</title>
    <!-- Gunakan Tailwind CDN khusus untuk halaman cetak agar ringan -->
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        /* CSS Khusus Print (Menghilangkan margin bawaan browser) */
        @media print {
            @page { margin: 0.5cm; }
            body { -webkit-print-color-adjust: exact; print-color-adjust: exact; }
            /* Menyembunyikan elemen dengan class 'print:hidden' saat dicetak */
            .print\:hidden { display: none !important; }
        }
    </style>
</head>
<body class="bg-gray-100 text-gray-800 font-sans p-8">

    <!-- Tombol Navigasi (Hanya muncul di layar komputer, hilang saat diprint) -->
    <div class="max-w-3xl mx-auto mb-6 print:hidden flex justify-between items-center">
        <a href="{{ route('transactions.index') }}" class="px-4 py-2 bg-gray-500 hover:bg-gray-600 text-white font-bold rounded shadow transition-colors">
            &larr; Kembali
        </a>
        <button onclick="window.print()" class="px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded shadow transition-colors flex items-center gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M5 4v3H4a2 2 0 00-2 2v3a2 2 0 002 2h1v2a2 2 0 002 2h6a2 2 0 002-2v-2h1a2 2 0 002-2V9a2 2 0 00-2-2h-1V4a2 2 0 00-2-2H7a2 2 0 00-2 2zm8 0H7v3h6V4zm0 8H7v4h6v-4z" clip-rule="evenodd" />
            </svg>
            Cetak Sekarang
        </button>
    </div>

    <!-- KERTAS INVOICE UTAMA -->
    <div class="max-w-3xl mx-auto bg-white p-10 border border-gray-200 shadow-sm print:shadow-none print:border-none">
        
        <!-- HEADER KOP SURAT -->
        <div class="border-b-4 border-gray-900 pb-6 mb-8 flex justify-between items-end">
            <div>
                <h1 class="text-3xl font-black text-gray-900 tracking-tight">BIRO JASA STNK</h1>
                <p class="text-gray-600 font-medium mt-1">Layanan Pengurusan Pajak Kendaraan Cepat & Aman</p>
                <p class="text-sm text-gray-500 mt-1">Jl. Contoh Alamat No. 123, Kota Anda &bull; WA: 0812-3456-7890</p>
            </div>
            <div class="text-right">
                <h2 class="text-3xl font-bold text-gray-300 uppercase tracking-widest">INVOICE</h2>
                <p class="font-bold text-gray-800 mt-2">#TRX-{{ str_pad($transaction->id, 5, '0', STR_PAD_LEFT) }}</p>
                <p class="text-sm text-gray-600">Tanggal: {{ \Carbon\Carbon::parse($transaction->created_at)->format('d F Y') }}</p>
            </div>
        </div>

        <!-- INFO KLIEN & KENDARAAN -->
        <div class="flex justify-between mb-8">
            <div class="w-1/2 pr-4">
                <h3 class="text-sm font-bold text-gray-400 uppercase tracking-wider mb-2">Informasi Klien</h3>
                <p class="font-bold text-lg text-gray-800">{{ $transaction->vehicle->client->nama_lengkap ?? 'Tidak Diketahui' }}</p>
                <p class="text-gray-600">No. WhatsApp: {{ $transaction->vehicle->client->no_whatsapp ?? '-' }}</p>
            </div>
            <div class="w-1/2 pl-4 border-l-2 border-gray-100">
                <h3 class="text-sm font-bold text-gray-400 uppercase tracking-wider mb-2">Data Kendaraan</h3>
                <p class="font-bold text-2xl text-gray-900">{{ $transaction->vehicle->nopol }}</p>
                <p class="text-gray-600 font-medium">{{ $transaction->vehicle->merk }} {{ $transaction->vehicle->tipe }}</p>
            </div>
        </div>

        <!-- TABEL RINCIAN -->
        <div class="mb-8">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-900 text-white">
                        <th class="py-3 px-4 font-bold text-sm">DESKRIPSI LAYANAN</th>
                        <th class="py-3 px-4 font-bold text-sm text-center">STATUS</th>
                        <th class="py-3 px-4 font-bold text-sm text-right">TOTAL BIAYA</th>
                    </tr>
                </thead>
                <tbody>
                    <tr class="border-b border-gray-200">
                        <td class="py-4 px-4">
                            <p class="font-bold text-gray-800 text-lg">{{ $transaction->jenis_layanan }}</p>
                            @if($transaction->catatan)
                                <p class="text-sm text-gray-500 mt-1">Catatan: {{ $transaction->catatan }}</p>
                            @endif
                        </td>
                        <td class="py-4 px-4 text-center">
                            @if($transaction->status_proses == 'Done')
                                <span class="font-bold text-green-700">LUNAS / SELESAI</span>
                            @else
                                <span class="font-bold text-yellow-600 uppercase">{{ $transaction->status_proses }}</span>
                            @endif
                        </td>
                        <td class="py-4 px-4 font-black text-gray-900 text-right text-xl">
                            Rp {{ number_format($transaction->total_biaya, 0, ',', '.') }}
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- FOOTER & TANDA TANGAN -->
        <div class="flex justify-between items-end mt-16 pt-8">
            <div class="text-sm text-gray-500 w-1/2">
                <p class="font-bold text-gray-700 mb-1">Perhatian:</p>
                <ul class="list-disc list-inside space-y-1">
                    <li>Harap simpan tanda terima ini sebagai bukti sah.</li>
                    <li>Estimasi pengerjaan bergantung pada sistem Samsat.</li>
                </ul>
            </div>
            
            <div class="text-center w-48">
                <p class="text-sm text-gray-600 mb-16">Hormat Kami,</p>
                <div class="border-b border-gray-800"></div>
                <p class="font-bold text-gray-800 mt-2">Biro Jasa STNK</p>
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
