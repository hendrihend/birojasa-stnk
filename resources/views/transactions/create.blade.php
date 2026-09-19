@extends('layouts.app')

@section('title', 'Buat Transaksi Baru')
@section('header_title', 'Mulai Pengurusan Berkas')

@section('content')
    <a href="{{ route('transactions.index') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-white border border-gray-300 rounded-lg text-gray-700 font-bold hover:bg-gray-50 transition-colors mb-6 shadow-sm">
        <i class="fa-solid fa-arrow-left"></i> Kembali ke Data Transaksi
    </a>

<div class="max-w-6xl mx-auto space-y-6">
    
    @if($errors->any())
        <div class="px-4 py-3 bg-red-50 border-l-4 border-red-500 text-red-700 rounded shadow-sm">
            <ul class="list-disc list-inside text-sm font-medium">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- ==================================================== -->
    <!-- TAHAP 1: FORM PENCARIAN KLIEN                        -->
    <!-- ==================================================== -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
        <form action="{{ route('transactions.create') }}" method="GET" class="flex flex-col md:flex-row gap-4 items-end">
            <div class="w-full">
                <label class="block text-sm font-bold text-gray-700 mb-1">Pilih Klien untuk Transaksi Kolektif</label>
                <select name="client_id" required class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none">
                    <option value="">-- Cari dan Pilih Klien --</option>
                    @foreach($clients as $client)
                        <option value="{{ $client->id }}" {{ request('client_id') == $client->id ? 'selected' : '' }}>
                            {{ $client->nama_lengkap }} ({{ $client->no_whatsapp }})
                        </option>
                    @endforeach
                </select>
            </div>
            <button type="submit" class="px-6 py-2.5 bg-gray-800 text-white font-bold rounded-lg hover:bg-gray-900 transition-colors whitespace-nowrap">
                Tampilkan Kendaraan
            </button>
        </form>
    </div>

    <!-- ==================================================== -->
    <!-- TAHAP 2: DAFTAR KENDARAAN (Muncul Setelah Klien Dipilih) -->
    <!-- ==================================================== -->
    @if($selectedClient && count($vehicles) > 0)
    <form action="{{ route('transactions.store') }}" method="POST" id="collectiveForm">
        @csrf
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <!-- HEADER GLOBAL -->
            <div class="p-6 border-b border-gray-100 bg-gray-50/50 flex flex-col md:flex-row justify-between gap-4 md:items-center">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-full bg-blue-100 flex items-center justify-center text-blue-600 text-lg">
                        <i class="fa-solid fa-file-signature"></i>
                    </div>
                    <div>
                        <h2 class="text-lg font-bold text-gray-800">Invoice Kolektif: {{ $selectedClient->nama_lengkap }}</h2>
                        <p class="text-xs text-gray-500">Centang kendaraan yang akan diurus dan isi rincian biayanya.</p>
                    </div>
                </div>
                
                <!-- Tanggal Masuk & Termin (Global untuk 1 Invoice) -->
                <div class="flex flex-wrap gap-3 w-full md:w-auto">
                    <div>
                        <!-- <label class="block text-[11px] font-bold text-gray-700 mb-1">Tgl Masuk Berkas *</label> -->
                        <input type="hidden" name="tgl_masuk" value="{{ old('tgl_masuk', date('Y-m-d')) }}" required class="w-full px-3 py-1.5 border border-gray-300 rounded focus:ring-1 outline-none text-sm">
                    </div>
                    <div>
                        <label class="block text-[11px] font-bold text-gray-700 mb-1">Dari Tgl (Mulai Termin) *</label>
                        <input type="date" name="tgl_mulai_termin" id="tgl_mulai_termin" value="{{ old('tgl_mulai_termin', date('Y-m-d')) }}" class="w-full px-3 py-1.5 border border-gray-300 rounded focus:ring-1 outline-none text-sm text-blue-700">
                    </div>
                    <div>
                        <label class="block text-[11px] font-bold text-gray-700 mb-1">Sampai Tgl (Jatuh Tempo) *</label>
                        <input type="date" name="tgl_jatuh_tempo" id="tgl_jatuh_tempo" class="w-full px-3 py-1.5 border border-gray-300 rounded focus:ring-1 outline-none text-sm text-red-600">
                    </div>
                </div>
            </div>

            <!-- LOOPING KENDARAAN KLIEN -->
            <div class="p-6 space-y-8">
                @foreach($vehicles as $vehicle)
                <div class="vehicle-card relative border border-gray-200 rounded-xl p-5 bg-white shadow-sm" data-vid="{{ $vehicle->id }}">
                    
                    <!-- Checkbox Seleksi -->
                    <div class="absolute -top-3 left-4 bg-white px-2">
                        <label class="flex items-center gap-2 cursor-pointer text-blue-700 font-bold">
                            <input type="checkbox" name="transaksi[{{ $vehicle->id }}][is_selected]" value="1" class="w-5 h-5 rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                            Proses Kendaraan Ini
                        </label>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-3">
                        <!-- Info Kendaraan & Status -->
                        <div>
                            <div class="mb-4">
                                <label class="block text-sm font-bold text-gray-700 mb-1">Kendaraan</label>
                                <input type="text" disabled value="{{ $vehicle->nopol }} - {{ $vehicle->merk }} {{ $vehicle->tipe }}" class="w-full px-4 py-2 border border-gray-200 rounded-lg bg-gray-50 text-gray-600 font-bold">
                            </div>
                            
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-xs font-bold text-gray-700 mb-1">Jenis Layanan *</label>
                                    <select name="transaksi[{{ $vehicle->id }}][jenis_layanan]" data-vid="{{ $vehicle->id }}" class="select-layanan w-full px-3 py-2 border border-gray-300 rounded-lg outline-none text-sm">
                                        <option value="Pajak Tahunan">Pajak Tahunan</option>
                                        <option value="Pajak 5 Tahunan">Pajak 5 Tahunan</option>
                                        <option value="Balik Nama">Balik Nama</option>
                                        <option value="Mutasi Keluar">Mutasi Keluar</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-gray-700 mb-1">Status Awal *</label>
                                    <select name="transaksi[{{ $vehicle->id }}][status_proses]" class="w-full px-3 py-2 border border-gray-300 rounded-lg outline-none text-sm text-blue-700 font-bold">
                                        <option value="Pending" selected>Pending</option>
                                        <option value="Cancel">Cancel</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <!-- Rincian Biaya (Pajak, Jasa, Lainnya) -->
                        <div class="bg-blue-50/50 p-4 rounded-xl border border-blue-100">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4 border-b border-blue-200 pb-4">
                                <div>
                                    <label class="block text-xs font-bold text-gray-700 mb-1">Biaya Pajak *</label>
                                    <div class="relative">
                                        <span class="absolute inset-y-0 left-3 flex items-center text-gray-500 font-bold text-sm">Rp</span>
                                        <input type="text" id="pajak_tampil_{{ $vehicle->id }}" placeholder="0" class="w-full pl-10 pr-3 py-2 border border-gray-300 rounded-lg focus:ring-1 focus:ring-blue-500 font-bold text-gray-800 text-sm">
                                        <input type="hidden" name="transaksi[{{ $vehicle->id }}][biaya_pajak]" id="pajak_asli_{{ $vehicle->id }}">
                                    </div>
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-gray-700 mb-1">Biaya Jasa *</label>
                                    <div class="relative">
                                        <span class="absolute inset-y-0 left-3 flex items-center text-gray-500 font-bold text-sm">Rp</span>
                                        <input type="text" id="jasa_tampil_{{ $vehicle->id }}" placeholder="0" class="w-full pl-10 pr-3 py-2 border border-gray-300 rounded-lg focus:ring-1 focus:ring-blue-500 font-bold text-gray-800 text-sm">
                                        <input type="hidden" name="transaksi[{{ $vehicle->id }}][biaya_jasa]" id="jasa_asli_{{ $vehicle->id }}">
                                    </div>
                                </div>
                            </div>

                            <label class="block text-xs font-bold text-gray-700 mb-2">Rincian Biaya Lainnya</label>
                            <div class="grid grid-cols-2 gap-3 bg-white p-3 rounded-lg border border-gray-200">
                                @php
                                    $rincianLain = [
                                        ['id' => 'loket_pendaftaran', 'label' => 'Loket Pendaftaran'],
                                        ['id' => 'loket_cek_fisik', 'label' => 'Cek Fisik'],
                                        ['id' => 'acc_tidak_hadir', 'label' => 'Acc Tidak Hadir STNK'],
                                        ['id' => 'acc_domisili', 'label' => 'Acc Domisili'],
                                        ['id' => 'loket_penetapan', 'label' => 'Penetapan'],
                                        ['id' => 'loket_pengesahan_1', 'label' => 'Pengesahan 1'],
                                        ['id' => 'loket_pengesahan_2', 'label' => 'Pengesahan 2'],
                                        ['id' => 'bea_materai', 'label' => 'Bea Materai'],
                                    ];
                                @endphp
                                @foreach($rincianLain as $item)
                                <div>
                                    <label id="label_{{ $item['id'] }}_{{ $vehicle->id }}" class="block text-[10px] font-bold text-gray-600 mb-1">{{ $item['label'] }}</label>
                                    <div class="relative">
                                        <span class="absolute inset-y-0 left-2 flex items-center text-gray-400 font-bold text-[10px]">Rp</span>
                                        <input type="text" id="tampil_{{ $item['id'] }}_{{ $vehicle->id }}" placeholder="0" class="input-detail-lain w-full pl-7 pr-2 py-1 border border-gray-300 rounded focus:ring-1 focus:ring-blue-500 font-bold text-gray-700 text-xs">
                                        <input type="hidden" name="transaksi[{{ $vehicle->id }}][{{ $item['id'] }}]" id="asli_{{ $item['id'] }}_{{ $vehicle->id }}">
                                    </div>
                                </div>
                                @endforeach
                            </div>
                            
                            <div class="mt-3 pt-3 border-t border-blue-200 flex justify-between items-center">
                                <span class="text-xs font-bold text-gray-600 uppercase">Subtotal Kendaraan</span>
                                <span class="text-lg font-black text-blue-700" id="total_kalkulasi_{{ $vehicle->id }}">Rp 0</span>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>

            <!-- TOMBOL ACTION GLOBAL -->
            <div class="p-6 bg-gray-50 border-t border-gray-100 flex gap-3">
                <button type="button" id="btnShowPreview" class="px-6 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-lg shadow-sm flex items-center gap-2">
                    <i class="fa-solid fa-eye"></i> Preview Invoice
                </button>
                <a href="{{ route('transactions.index') }}" class="px-6 py-2.5 bg-white border border-gray-300 hover:bg-gray-50 text-gray-700 font-bold rounded-lg">
                    Batal
                </a>
            </div>
        </div>
    </form>
    @elseif($selectedClient && count($vehicles) == 0)
        <div class="p-5 bg-yellow-50 border border-yellow-200 rounded-xl text-yellow-800 font-medium">
            <i class="fa-solid fa-circle-exclamation mr-2"></i> Klien ini belum memiliki data kendaraan di sistem.
        </div>
    @endif

    <!-- ==================================================== -->
    <!-- MODAL PREVIEW INVOICE KOLEKTIF (GAYA KERTAS PRINT)   -->
    <!-- ==================================================== -->
    <div id="previewModal" class="hidden fixed inset-0 z-50 bg-gray-900/70 backdrop-blur-sm overflow-y-auto flex items-center justify-center p-4 transition-opacity">
        <div class="bg-white shadow-2xl w-full max-w-5xl flex flex-col scale-100 max-h-[95vh] relative">
            
            <!-- Tombol Close Melayang -->
            <button type="button" id="btnCloseModal" class="absolute right-4 top-4 text-gray-400 hover:text-red-500 hover:bg-red-50 w-10 h-10 rounded-full flex items-center justify-center transition-colors z-10">
                <i class="fa-solid fa-xmark text-2xl"></i>
            </button>

            <!-- KERTAS INVOICE (Isi Modal) -->
            <div class="p-10 overflow-y-auto bg-white flex-1 relative border-b-4 border-blue-600">
                
                <!-- HEADER KOP SURAT -->
                <div class="flex justify-between items-start mb-8">
                    <div class="w-1/2">
                        <div class="flex items-center gap-2 mb-2">
                            <div class="w-12 h-12 bg-blue-900 flex items-center justify-center text-white font-black text-2xl rounded-sm">BJ</div>
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
                            <tr><td class="py-1 text-gray-600">No Invoice</td><td class="py-1 font-bold">: [ Dibuat Otomatis ]</td></tr>
                            <tr><td class="py-1 text-gray-600">Tgl. Pembuatan</td><td class="py-1 font-bold" id="prevInvDate">: -</td></tr>
                            <tr><td class="py-1 text-gray-600">Termin (hari)</td><td class="py-1 font-bold" id="prevTerminHari">: -</td></tr>
                        </table>
                    </div>
                </div>

                <!-- INFO KEPADA YTH -->
                <div class="mb-6">
                    <p class="text-sm text-gray-700 mb-1">Kepada Yth :</p>
                    <h3 class="font-bold text-lg text-gray-900 uppercase">{{ $selectedClient->nama_lengkap ?? '' }}</h3>
                    <p class="text-xs text-gray-700 mt-1">{{ $selectedClient->alamat ?? '-' }}</p>
                    <p class="text-xs text-gray-700 mt-1">No. WhatsApp / Telp: {{ $selectedClient->no_whatsapp ?? '-' }}</p>
                </div>

                <p class="text-sm text-gray-800 mb-2">Dengan ini, kami menyampaikan pengajuan atas <strong>Layanan Biro Jasa Kolektif</strong> <span class="float-right underline text-xs">Kendaraan Terlampir:</span></p>

                <!-- TABEL UTAMA (RINCIAN KENDARAAN) -->
                <table class="w-full text-sm border-collapse border border-gray-900 mb-6">
                    <thead>
                        <tr class="bg-gray-100">
                            <th class="border border-gray-900 py-2 px-2 text-center w-8">No</th>
                            <th class="border border-gray-900 py-2 px-2 text-center">No Polisi</th>
                            <th class="border border-gray-900 py-2 px-2 text-center">Merk / Type</th>
                            <th class="border border-gray-900 py-2 px-2 text-center">Layanan</th>
                            <th class="border border-gray-900 py-2 px-2 text-center">Pajak</th>
                            <th class="border border-gray-900 py-2 px-2 text-center">Jasa</th>
                            <th class="border border-gray-900 py-2 px-2 text-center">Biaya Lain</th>
                            <th class="border border-gray-900 py-2 px-2 text-center w-28">Total</th>
                        </tr>
                    </thead>
                    <tbody id="previewTableBody">
                        <!-- Baris akan diisi JS -->
                    </tbody>
                    <tfoot>
                        <tr class="bg-gray-100 font-bold">
                            <td colspan="4" class="border border-gray-900 py-3 px-3 text-right tracking-widest text-sm uppercase">Grand Total</td>
                            <td class="border border-gray-900 py-3 px-3 text-right text-xs" id="prevGrandTotalPajak">0</td>
                            <td class="border border-gray-900 py-3 px-3 text-right text-xs" id="prevGrandTotalJasa">0</td>
                            <td class="border border-gray-900 py-3 px-3 text-right text-xs" id="prevGrandTotalLain">0</td>
                            <td class="border border-gray-900 py-3 px-3 text-right flex justify-between text-sm">
                                <span>Rp</span> <span id="prevGrandTotalBiaya">0</span>
                            </td>
                        </tr>
                    </tfoot>
                </table>

                <!-- TERBILANG -->
                <div class="mb-10 text-sm">
                    <div class="flex mb-2 items-center">
                        <div class="w-32 text-gray-700 uppercase font-bold text-xs tracking-wider">TERBILANG:</div>
                        <div class="font-bold italic flex-1 capitalize bg-gray-100 px-3 py-1.5 rounded" id="prevTerbilang">** Nol Rupiah **</div>
                    </div>

                    <!-- TAMBAHAN BARU: CATATAN JATUH TEMPO -->
                    <div class="flex items-start mt-4">
                        <div class="w-32 text-gray-700 font-bold text-xs mt-1">CATATAN:</div>
                        <div class="flex-1 bg-yellow-50 border border-yellow-200 px-3 py-2 text-xs text-gray-800 rounded" id="prevCatatan">
                            <!-- Teks catatan akan diisi otomatis oleh JS -->
                        </div>
                    </div>
                </div>

                <!-- TABEL RINCIAN BIAYA LAIN (GABUNGAN SEMUA KENDARAAN) -->
                <div class="flex justify-between items-start text-sm">
                    <div class="w-1/2">
                        <p class="font-bold text-gray-700 uppercase">Rincian Biaya Lainnya :</p>
                        <p class="text-xs text-gray-500 italic mt-1">Akumulasi total dari seluruh kendaraan di atas.</p>
                    </div>
                    <div class="w-1/2 flex justify-end">
                        <table class="w-[85%] border-collapse border border-gray-900 text-xs">
                            <tbody>
                                <tr>
                                    <td class="border border-gray-900 px-3 py-1.5 text-gray-700">Loket Pendaftaran</td>
                                    <td class="border border-gray-900 px-3 py-1.5 w-8">Rp</td>
                                    <td class="border border-gray-900 px-3 py-1.5 text-right font-medium" id="prevPendaftaran">-</td>
                                </tr>
                                <tr>
                                    <td class="border border-gray-900 px-3 py-1.5 text-gray-700">Loket Cek Fisik</td>
                                    <td class="border border-gray-900 px-3 py-1.5">Rp</td>
                                    <td class="border border-gray-900 px-3 py-1.5 text-right font-medium" id="prevCekFisik">-</td>
                                </tr>
                                <tr>
                                    <!-- LABEL INI AKAN BERUBAH OTOMATIS OLEH JAVASCRIPT -->
                                    <td class="border border-gray-900 px-3 py-1.5 text-gray-700 transition-colors" id="prevLabelAcc">Acc Tidak Hadir STNK</td>
                                    <td class="border border-gray-900 px-3 py-1.5">Rp</td>
                                    <td class="border border-gray-900 px-3 py-1.5 text-right font-medium" id="prevAccTidakHadir">-</td>
                                </tr>
                                <tr>
                                    <td class="border border-gray-900 px-3 py-1.5 text-gray-700">Acc Domisili (Beda Alamat)</td>
                                    <td class="border border-gray-900 px-3 py-1.5">Rp</td>
                                    <td class="border border-gray-900 px-3 py-1.5 text-right font-medium" id="prevAccDomisili">-</td>
                                </tr>
                                <tr>
                                    <td class="border border-gray-900 px-3 py-1.5 text-gray-700">Loket Penetapan</td>
                                    <td class="border border-gray-900 px-3 py-1.5">Rp</td>
                                    <td class="border border-gray-900 px-3 py-1.5 text-right font-medium" id="prevPenetapan">-</td>
                                </tr>
                                <tr>
                                    <td class="border border-gray-900 px-3 py-1.5 text-gray-700">Loket Pengesahan Pertama</td>
                                    <td class="border border-gray-900 px-3 py-1.5">Rp</td>
                                    <td class="border border-gray-900 px-3 py-1.5 text-right font-medium" id="prevPengesahan1">-</td>
                                </tr>
                                <tr>
                                    <td class="border border-gray-900 px-3 py-1.5 text-gray-700">Loket Pengesahan Kedua</td>
                                    <td class="border border-gray-900 px-3 py-1.5">Rp</td>
                                    <td class="border border-gray-900 px-3 py-1.5 text-right font-medium" id="prevPengesahan2">-</td>
                                </tr>
                                <tr>
                                    <td class="border border-gray-900 px-3 py-1.5 text-gray-700">Bea Materai</td>
                                    <td class="border border-gray-900 px-3 py-1.5">Rp</td>
                                    <td class="border border-gray-900 px-3 py-1.5 text-right font-medium" id="prevMaterai">-</td>
                                </tr>
                                <tr class="font-bold bg-gray-100">
                                    <td class="border border-gray-900 px-3 py-2 text-right">Total Biaya Lain</td>
                                    <td class="border border-gray-900 px-3 py-2">Rp</td>
                                    <td class="border border-gray-900 px-3 py-2 text-right" id="prevTotalLainBawah">-</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>

            <!-- Modal Footer (Tombol Aksi) -->
            <div class="px-6 py-4 bg-gray-100 flex justify-end gap-3 border-t border-gray-200">
                <button type="button" id="btnCancelModal" class="px-6 py-2.5 bg-white border border-gray-300 hover:bg-gray-50 text-gray-700 font-bold rounded-lg transition-colors">
                    Cek Lagi
                </button>
                <button type="button" id="btnSubmitReal" class="px-8 py-2.5 bg-green-600 hover:bg-green-700 text-white font-bold rounded-lg shadow-sm transition-colors flex items-center gap-2">
                    <i class="fa-solid fa-check"></i> Konfirmasi & Simpan
                </button>
            </div>
        </div>
    </div>
</div>

<!-- SCRIPT UNTUK FORMAT RUPIAH MULTIPLE KENDARAAN -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const formatRupiah = (angka) => new Intl.NumberFormat('id-ID').format(angka || 0);

        // Ambil semua elemen kendaraan (masing-masing punya form mandiri)
        const vehicleCards = document.querySelectorAll('.vehicle-card');

        vehicleCards.forEach(card => {
            const vid = card.dataset.vid; // Ambil ID kendaraan dari atribut data-vid
            
            const pajakTampil = document.getElementById(`pajak_tampil_${vid}`);
            const pajakAsli = document.getElementById(`pajak_asli_${vid}`);
            const jasaTampil = document.getElementById(`jasa_tampil_${vid}`);
            const jasaAsli = document.getElementById(`jasa_asli_${vid}`);
            const labelTotal = document.getElementById(`total_kalkulasi_${vid}`);
            
            // Ambil semua input rincian lain HANYA di dalam card kendaraan ini
            const detailLainInputs = card.querySelectorAll('.input-detail-lain');

            const hitungTotal = () => {
                let totalPajak = parseInt(pajakAsli.value) || 0;
                let totalJasa = parseInt(jasaAsli.value) || 0;
                let subtotalLain = 0;

                detailLainInputs.forEach(inputTampil => {
                    let idAsli = inputTampil.id.replace('tampil_', 'asli_');
                    let inputAsli = document.getElementById(idAsli);
                    let nilai = parseInt(inputAsli.value) || 0;
                    subtotalLain += nilai;
                    
                    if(inputAsli.value > 0 && document.activeElement !== inputTampil) {
                        inputTampil.value = formatRupiah(nilai);
                    }
                });

                if(pajakAsli.value > 0 && document.activeElement !== pajakTampil) pajakTampil.value = formatRupiah(totalPajak);
                if(jasaAsli.value > 0 && document.activeElement !== jasaTampil) jasaTampil.value = formatRupiah(totalJasa);
                
                labelTotal.innerText = 'Rp ' + formatRupiah(totalPajak + totalJasa + subtotalLain);
            };

            const attachListener = (tampil, asli) => {
                tampil.addEventListener('input', function(e) {
                    let angkaMurni = this.value.replace(/[^0-9]/g, '');
                    asli.value = angkaMurni;
                    this.value = angkaMurni ? formatRupiah(angkaMurni) : '';
                    hitungTotal();
                });
            };

            attachListener(pajakTampil, pajakAsli);
            attachListener(jasaTampil, jasaAsli);
            
            detailLainInputs.forEach(inputTampil => {
                let idAsli = inputTampil.id.replace('tampil_', 'asli_');
                attachListener(inputTampil, document.getElementById(idAsli));
            });

            hitungTotal();
        });

        // LOGIKA PERUBAHAN LABEL ACC TIDAK HADIR
        // =======================================================
        const selectLayananElems = document.querySelectorAll('.select-layanan');
        
        // Fungsi untuk memperbarui label spesifik milik 1 kendaraan
        const updateLabelAcc = (selectElem) => {
            const vid = selectElem.dataset.vid; // Ambil ID kendaraan
            const labelAcc = document.getElementById(`label_acc_tidak_hadir_${vid}`);
            
            if (labelAcc) {
                if (selectElem.value === 'Pajak 5 Tahunan') {
                    labelAcc.innerText = 'Acc Tidak Hadir Unit';
                    labelAcc.classList.add('text-blue-600'); // Beri warna beda sbg penanda (opsional)
                } else {
                    labelAcc.innerText = 'Acc Tidak Hadir STNK';
                    labelAcc.classList.remove('text-blue-600');
                }
            }
        };

        // Terapkan ke semua dropdown jenis_layanan
        selectLayananElems.forEach(select => {
            // 1. Jalankan sekali saat halaman dimuat (untuk old value)
            updateLabelAcc(select);
            
            // 2. Jalankan setiap kali admin mengubah pilihan dropdown
            select.addEventListener('change', function() {
                updateLabelAcc(this);
            });
        });

        // ==============================================================
        // LOGIKA POP-UP PREVIEW INVOICE (BARU)
        // ==============================================================
        const previewTableBody = document.getElementById('previewTableBody');
        const previewGrandTotal = document.getElementById('previewGrandTotal');
        
        // Fungsi Terbilang Javascript
        function penyebut(nilai) {
            nilai = Math.abs(nilai);
            const huruf = ["", "Satu", "Dua", "Tiga", "Empat", "Lima", "Enam", "Tujuh", "Delapan", "Sembilan", "Sepuluh", "Sebelas"];
            let temp = "";
            if (nilai < 12) { temp = " " + huruf[nilai]; }
            else if (nilai < 20) { temp = penyebut(nilai - 10) + " Belas"; }
            else if (nilai < 100) { temp = penyebut(Math.floor(nilai / 10)) + " Puluh" + penyebut(nilai % 10); }
            else if (nilai < 200) { temp = " Seratus" + penyebut(nilai - 100); }
            else if (nilai < 1000) { temp = penyebut(Math.floor(nilai / 100)) + " Ratus" + penyebut(nilai % 100); }
            else if (nilai < 2000) { temp = " Seribu" + penyebut(nilai - 1000); }
            else if (nilai < 1000000) { temp = penyebut(Math.floor(nilai / 1000)) + " Ribu" + penyebut(nilai % 1000); }
            else if (nilai < 1000000000) { temp = penyebut(Math.floor(nilai / 1000000)) + " Juta" + penyebut(nilai % 1000000); }
            return temp;
        }
        function terbilangJS(nilai) {
            if (nilai === 0) return "Nol Rupiah";
            let hasil = nilai < 0 ? "Minus " + penyebut(nilai).trim() : penyebut(nilai).trim();
            return hasil + " Rupiah";
        }

        const btnShowPreview = document.getElementById('btnShowPreview');
        const previewModal = document.getElementById('previewModal');
        
        if(btnShowPreview) {
            btnShowPreview.addEventListener('click', function() {
                let htmlContent = '';
                let grandPajak = 0, grandJasa = 0, grandLain = 0, grandSemua = 0;
                let sumPendaftaran = 0, sumCekFisik = 0, sumAccTidakHadir = 0, sumAccDomisili = 0;
                let sumPenetapan = 0, sumPengesahan1 = 0, sumPengesahan2 = 0, sumMaterai = 0;
                let selectedCount = 0;
                let has5Tahunan = false; // Penanda untuk Acc Unit

                document.querySelectorAll('.vehicle-card').forEach(card => {
                    const vid = card.dataset.vid;
                    const checkbox = card.querySelector(`input[name="transaksi[${vid}][is_selected]"]`);
                    
                    if (checkbox && checkbox.checked) {
                        selectedCount++;
                        
                        // 1. Ambil Nama Kendaraan & Layanan
                        const nopolText = card.querySelector('input[disabled]').value;
                        const splitText = nopolText.split(' - ');
                        const nopol = splitText[0];
                        const merk = splitText[1] || '-';

                        const layananElem = card.querySelector(`select[name="transaksi[${vid}][jenis_layanan]"]`);
                        const layanan = layananElem.options[layananElem.selectedIndex].text;
                        
                        // DETEKSI 5 TAHUNAN
                        if (layananElem.value === 'Pajak 5 Tahunan') has5Tahunan = true;
                        
                        // 2. Ambil Biaya
                        const pajak = parseInt(document.getElementById(`pajak_asli_${vid}`).value) || 0;
                        const jasa = parseInt(document.getElementById(`jasa_asli_${vid}`).value) || 0;
                        
                        const pend = parseInt(document.getElementById(`asli_loket_pendaftaran_${vid}`).value) || 0;
                        const cekf = parseInt(document.getElementById(`asli_loket_cek_fisik_${vid}`).value) || 0;
                        const acc = parseInt(document.getElementById(`asli_acc_tidak_hadir_${vid}`).value) || 0;
                        const dom = parseInt(document.getElementById(`asli_acc_domisili_${vid}`).value) || 0;
                        const pntp = parseInt(document.getElementById(`asli_loket_penetapan_${vid}`).value) || 0;
                        const psh1 = parseInt(document.getElementById(`asli_loket_pengesahan_1_${vid}`).value) || 0;
                        const psh2 = parseInt(document.getElementById(`asli_loket_pengesahan_2_${vid}`).value) || 0;
                        const mtr = parseInt(document.getElementById(`asli_bea_materai_${vid}`).value) || 0;

                        const subLain = pend + cekf + acc + dom + pntp + psh1 + psh2 + mtr;
                        const subTotal = pajak + jasa + subLain;

                        // Tambahkan ke Grand Total
                        sumPendaftaran += pend; sumCekFisik += cekf; sumAccTidakHadir += acc; sumAccDomisili += dom;
                        sumPenetapan += pntp; sumPengesahan1 += psh1; sumPengesahan2 += psh2; sumMaterai += mtr;

                        grandPajak += pajak; grandJasa += jasa; grandLain += subLain; grandSemua += subTotal;

                        // 3. Susun Baris Tabel HTML (Border Kotak-Kotak Ala Invoice)
                        htmlContent += `
                            <tr>
                                <td class="border border-gray-900 py-2 px-2 text-center">${selectedCount}</td>
                                <td class="border border-gray-900 py-2 px-2 text-center text-xs font-bold">${nopol}</td>
                                <td class="border border-gray-900 py-2 px-2 text-center text-xs">${merk}</td>
                                <td class="border border-gray-900 py-2 px-2 text-center text-xs">${layanan}</td>
                                <td class="border border-gray-900 py-2 px-2 text-right text-xs">${formatRupiah(pajak)}</td>
                                <td class="border border-gray-900 py-2 px-2 text-right text-xs">${formatRupiah(jasa)}</td>
                                <td class="border border-gray-900 py-2 px-2 text-right text-xs">${formatRupiah(subLain)}</td>
                                <td class="border border-gray-900 py-2 px-2 text-right font-bold flex justify-between text-xs">
                                    <span>Rp</span> <span>${formatRupiah(subTotal)}</span>
                                </td>
                            </tr>
                        `;
                    }
                });

                if (selectedCount === 0) {
                    alert('Silakan centang minimal 1 kendaraan yang ingin diproses!');
                    return;
                }

                // Tempelkan data ke dalam Modal
                document.getElementById('previewTableBody').innerHTML = htmlContent;
                
                // Header Date
                const inputTglMasuk = document.querySelector('input[name="tgl_masuk"]');
                document.getElementById('prevInvDate').innerText = `: ${inputTglMasuk.value.split('-').reverse().join('/')}`;

                // Set Total Kalkulasi
                document.getElementById('prevGrandTotalPajak').innerText = formatRupiah(grandPajak);
                document.getElementById('prevGrandTotalJasa').innerText = formatRupiah(grandJasa);
                document.getElementById('prevGrandTotalLain').innerText = formatRupiah(grandLain);
                document.getElementById('prevGrandTotalBiaya').innerText = formatRupiah(grandSemua);
                document.getElementById('prevTotalLainBawah').innerText = formatRupiah(grandLain);
                
                // Set Terbilang
                document.getElementById('prevTerbilang').innerText = '** ' + terbilangJS(grandSemua) + ' **';

                // Set Rincian Biaya Lain
                document.getElementById('prevPendaftaran').innerText = sumPendaftaran > 0 ? formatRupiah(sumPendaftaran) : '-';
                document.getElementById('prevCekFisik').innerText = sumCekFisik > 0 ? formatRupiah(sumCekFisik) : '-';
                document.getElementById('prevAccTidakHadir').innerText = sumAccTidakHadir > 0 ? formatRupiah(sumAccTidakHadir) : '-';
                document.getElementById('prevAccDomisili').innerText = sumAccDomisili > 0 ? formatRupiah(sumAccDomisili) : '-';
                document.getElementById('prevPenetapan').innerText = sumPenetapan > 0 ? formatRupiah(sumPenetapan) : '-';
                document.getElementById('prevPengesahan1').innerText = sumPengesahan1 > 0 ? formatRupiah(sumPengesahan1) : '-';
                document.getElementById('prevPengesahan2').innerText = sumPengesahan2 > 0 ? formatRupiah(sumPengesahan2) : '-';
                document.getElementById('prevMaterai').innerText = sumMaterai > 0 ? formatRupiah(sumMaterai) : '-';

                // LOGIKA PERUBAHAN NAMA LABEL ACC (BERDASARKAN LAYANAN KENDARAAN YG DIPILIH)
                const prevLabelAcc = document.getElementById('prevLabelAcc');
                if (has5Tahunan) {
                    prevLabelAcc.innerText = 'Acc Tidak Hadir Unit';
                    prevLabelAcc.classList.add('text-blue-700');
                } else {
                    prevLabelAcc.innerText = 'Acc Tidak Hadir STNK';
                    prevLabelAcc.classList.remove('text-blue-700');
                }

                // Tampilkan Modal
                previewModal.classList.remove('hidden');
                
                // ==========================================
                // LOGIKA HITUNG SELISIH HARI (TERMIN) OTOMATIS
                // ==========================================
                const formatTgl = (tglStr) => tglStr ? tglStr.split('-').reverse().join('/') : '-';
                
                const tglMulaiVal = document.getElementById('tgl_mulai_termin').value;
                const tglJatuhVal = document.getElementById('tgl_jatuh_tempo').value;
                
                if(tglMulaiVal && tglJatuhVal) {
                    const date1 = new Date(tglMulaiVal);
                    const date2 = new Date(tglJatuhVal);
                    
                    // Hitung selisih waktu (dalam milidetik) lalu konversi ke Hari
                    const diffTime = date2 - date1;
                    const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));
                    
                    if (diffDays >= 0) {
                        document.getElementById('prevTerminHari').innerText = ': ' + diffDays;
                        document.getElementById('prevCatatan').innerHTML = `Pembayaran tagihan ini memiliki termin <strong>${diffDays} Hari</strong> terhitung sejak tanggal berkas diterima (${formatTgl(tglMulaiVal)}). Jatuh tempo pembayaran pada tanggal <strong class="text-red-600">${formatTgl(tglJatuhVal)}</strong>.`;
                    } else {
                        document.getElementById('prevCatatan').innerHTML = `<span class="text-red-500 italic"><i class="fa-solid fa-triangle-exclamation"></i> Tanggal Jatuh Tempo tidak boleh mundur dari Tanggal Mulai!</span>`;
                    }
                } else {
                    document.getElementById('prevCatatan').innerHTML = `<span class="text-red-500 italic">Silakan isi tanggal mulai dan jatuh tempo di form terlebih dahulu!</span>`;
                }
            });
        }

        // Aksi Tutup Modal
        const closeModal = () => {
            if(previewModal) previewModal.classList.add('hidden');
        };
        document.getElementById('btnCloseModal')?.addEventListener('click', closeModal);
        document.getElementById('btnCancelModal')?.addEventListener('click', closeModal);

        // Aksi Eksekusi Form Sebenarnya
        document.getElementById('btnSubmitReal')?.addEventListener('click', function() {
            // Tampilkan efek loading opsional pada tombol
            this.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Menyimpan...';
            this.disabled = true;
            
            // Submit Form yang sesungguhnya!
            document.getElementById('collectiveForm').submit();
        });
    });
</script>

@endsection
