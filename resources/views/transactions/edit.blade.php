@extends('layouts.app')

@section('title', 'Edit Transaksi')
@section('header_title', 'Update Status Pengurusan')

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

        <form id="editForm" action="{{ route('transactions.update', $transactions->first()->id) }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                
                <!-- HEADER GLOBAL -->
                <div class="p-6 border-b border-gray-100 bg-gray-50/50 flex flex-col md:flex-row justify-between gap-4 md:items-center">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-full bg-yellow-100 flex items-center justify-center text-yellow-600 text-lg shadow-sm">
                            <i class="fa-solid fa-pen-to-square"></i>
                        </div>
                        <div>
                            <h2 class="text-lg font-bold text-gray-800">Edit Invoice: {{ $baseInvoice }}</h2>
                            <p class="text-xs text-gray-500">Klien: <span class="font-bold text-gray-700">{{ $client->nama_lengkap ?? 'Tanpa Nama' }}</span> ({{ $client->no_whatsapp ?? '-' }})</p>
                        </div>
                    </div>
                    
                    <!-- ============================================== -->
                    <!-- Tanggal Masuk, Selesai & Termin (Global)       -->
                    <!-- ============================================== -->
                    <div class="flex flex-wrap gap-3 w-full md:w-auto mt-4 md:mt-0">
                        <div>
                            <!-- <label class="block text-[11px] font-bold text-gray-700 mb-1">Tgl Masuk Berkas *</label> -->
                            <input type="hidden" name="tgl_masuk" value="{{ \Carbon\Carbon::parse($transactions->first()->tgl_masuk)->format('Y-m-d') }}" required class="w-full px-3 py-1.5 border border-gray-300 rounded focus:ring-1 outline-none text-sm bg-gray-50">
                        </div>
                        <div>
                            <label class="block text-[11px] font-bold text-gray-700 mb-1">Dari Tgl (Termin) *</label>
                            <input type="date" name="tgl_mulai_termin" value="{{ $transactions->first()->tgl_mulai_termin ? \Carbon\Carbon::parse($transactions->first()->tgl_mulai_termin)->format('Y-m-d') : '' }}" required class="w-full px-3 py-1.5 border border-blue-300 bg-blue-50/30 rounded focus:ring-1 focus:ring-blue-500 outline-none text-sm text-blue-700">
                        </div>
                        <div>
                            <label class="block text-[11px] font-bold text-gray-700 mb-1">Sampai Tgl (Jatuh Tempo) *</label>
                            <input type="date" name="tgl_jatuh_tempo" value="{{ $transactions->first()->tgl_jatuh_tempo ? \Carbon\Carbon::parse($transactions->first()->tgl_jatuh_tempo)->format('Y-m-d') : '' }}" required class="w-full px-3 py-1.5 border border-red-300 bg-red-50/30 rounded focus:ring-1 focus:ring-red-500 outline-none text-sm text-red-600">
                        </div>
                        <div>
                            <!-- <label class="block text-[11px] font-bold text-gray-700 mb-1">Tgl Selesai (Opsional)</label> -->
                            <input type="hidden" name="tgl_selesai" value="{{ $transactions->first()->tgl_selesai ? \Carbon\Carbon::parse($transactions->first()->tgl_selesai)->format('Y-m-d') : '' }}" class="w-full px-3 py-1.5 border border-gray-300 rounded focus:ring-1 outline-none text-sm">
                        </div>
                    </div>
                </div>

                <!-- LOOPING TRANSAKSI DALAM INVOICE INI -->
                <div class="p-6 space-y-8 bg-gray-50/30">
                    @foreach($transactions as $trx)
                    <div class="trx-card relative border border-gray-200 rounded-xl p-6 bg-white shadow-sm hover:shadow-md transition-shadow" data-tid="{{ $trx->id }}">
                        
                        <div class="absolute -top-3 left-4 bg-white px-3 py-0.5 text-[10px] font-black text-gray-500 border border-gray-200 rounded-full shadow-sm tracking-wider">
                            TRX ID: {{ $trx->id }}
                        </div>

                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mt-2">
                            <!-- Kolom Kiri: Info Kendaraan & Status -->
                            <div>
                                <div class="mb-5">
                                    <label class="block text-xs font-bold text-gray-600 mb-1 uppercase tracking-wide">Kendaraan</label>
                                    <input type="text" disabled value="{{ $trx->vehicle->nopol }} - {{ $trx->vehicle->merk }} {{ $trx->vehicle->tipe }}" class="w-full px-4 py-2 border border-gray-200 rounded-lg bg-gray-100 text-gray-700 font-bold">
                                </div>
                                
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-xs font-bold text-gray-600 mb-1">Jenis Layanan *</label>
                                        <!-- TAMBAHKAN CLASS 'select-layanan' DAN 'data-vid' DI SINI -->
                                        <select name="transaksi[{{ $trx->id }}][jenis_layanan]" data-vid="{{ $trx->id }}" class="select-layanan w-full px-3 py-2 border border-gray-300 rounded-lg outline-none text-sm focus:ring-1 focus:ring-blue-500">
                                            <option value="Pajak Tahunan" {{ $trx->jenis_layanan == 'Pajak Tahunan' ? 'selected' : '' }}>Pajak Tahunan</option>
                                            <option value="Pajak 5 Tahunan" {{ $trx->jenis_layanan == 'Pajak 5 Tahunan' ? 'selected' : '' }}>Pajak 5 Tahunan</option>
                                            <option value="Balik Nama" {{ $trx->jenis_layanan == 'Balik Nama' ? 'selected' : '' }}>Balik Nama</option>
                                            <option value="Mutasi Keluar" {{ $trx->jenis_layanan == 'Mutasi Keluar' ? 'selected' : '' }}>Mutasi Keluar</option>
                                        </select>
                                    </div>
                                    <div>
                                        <label class="block text-xs font-bold text-gray-600 mb-1">Status Pengerjaan *</label>
                                        <select name="transaksi[{{ $trx->id }}][status_proses]" class="w-full px-3 py-2 border border-gray-300 rounded-lg outline-none text-sm font-bold {{ $trx->status_proses == 'Done' ? 'text-green-700 bg-green-50' : ($trx->status_proses == 'Cancel' ? 'text-red-700 bg-red-50' : 'text-blue-700 bg-blue-50') }}">
                                            <option value="Pending" {{ $trx->status_proses == 'Pending' ? 'selected' : '' }}>Pending</option>
                                            <option value="Done" {{ $trx->status_proses == 'Done' ? 'selected' : '' }}>Done (Selesai)</option>
                                            <option value="Cancel" {{ $trx->status_proses == 'Cancel' ? 'selected' : '' }}>Cancel</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <!-- Kolom Kanan: Rincian Biaya -->
                            <div class="bg-blue-50/50 p-5 rounded-xl border border-blue-100 relative">
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-5 border-b border-blue-200 pb-5">
                                    <div>
                                        <label class="block text-xs font-bold text-gray-700 mb-1">Biaya Pajak</label>
                                        <div class="relative">
                                            <span class="absolute inset-y-0 left-3 flex items-center text-gray-500 font-bold text-sm">Rp</span>
                                            <input type="text" id="pajak_tampil_{{ $trx->id }}" value="{{ $trx->biaya_pajak }}" class="w-full pl-10 pr-3 py-2 border border-gray-300 rounded-lg focus:ring-1 focus:ring-blue-500 font-bold text-gray-800 text-sm">
                                            <input type="hidden" name="transaksi[{{ $trx->id }}][biaya_pajak]" id="pajak_asli_{{ $trx->id }}" value="{{ $trx->biaya_pajak }}">
                                        </div>
                                    </div>
                                    <div>
                                        <label class="block text-xs font-bold text-gray-700 mb-1">Biaya Jasa</label>
                                        <div class="relative">
                                            <span class="absolute inset-y-0 left-3 flex items-center text-gray-500 font-bold text-sm">Rp</span>
                                            <input type="text" id="jasa_tampil_{{ $trx->id }}" value="{{ $trx->biaya_jasa }}" class="w-full pl-10 pr-3 py-2 border border-gray-300 rounded-lg focus:ring-1 focus:ring-blue-500 font-bold text-gray-800 text-sm">
                                            <input type="hidden" name="transaksi[{{ $trx->id }}][biaya_jasa]" id="jasa_asli_{{ $trx->id }}" value="{{ $trx->biaya_jasa }}">
                                        </div>
                                    </div>
                                </div>

                                <label class="block text-[11px] font-bold text-gray-500 mb-2 uppercase tracking-wider">Rincian Biaya Lainnya</label>
                                <div class="grid grid-cols-2 gap-x-4 gap-y-3 bg-white p-4 rounded-lg border border-gray-200 shadow-inner">
                                    @php
                                        $rincianLain = [
                                            ['id' => 'loket_pendaftaran', 'label' => 'Loket Pendaftaran', 'val' => $trx->loket_pendaftaran],
                                            ['id' => 'loket_cek_fisik', 'label' => 'Cek Fisik', 'val' => $trx->loket_cek_fisik],
                                            // Ubah label default Acc Tidak Hadir STNK di sini
                                            ['id' => 'acc_tidak_hadir', 'label' => 'Acc Tdk Hadir STNK', 'val' => $trx->acc_tidak_hadir],
                                            ['id' => 'acc_domisili', 'label' => 'Acc Domisili', 'val' => $trx->acc_domisili],
                                            ['id' => 'loket_penetapan', 'label' => 'Penetapan', 'val' => $trx->loket_penetapan],
                                            ['id' => 'loket_pengesahan_1', 'label' => 'Pengesahan 1', 'val' => $trx->loket_pengesahan_1],
                                            ['id' => 'loket_pengesahan_2', 'label' => 'Pengesahan 2', 'val' => $trx->loket_pengesahan_2],
                                            ['id' => 'bea_materai', 'label' => 'Bea Materai', 'val' => $trx->bea_materai],
                                        ];
                                    @endphp
                                    @foreach($rincianLain as $item)
                                    <div>
                                        <!-- TAMBAHKAN ID PADA TAG LABEL -->
                                        <label id="label_{{ $item['id'] }}_{{ $trx->id }}" class="block text-[10px] font-bold text-gray-600 mb-1">{{ $item['label'] }}</label>
                                        <div class="relative">
                                            <span class="absolute inset-y-0 left-2 flex items-center text-gray-400 font-bold text-[10px]">Rp</span>
                                            <input type="text" id="tampil_{{ $item['id'] }}_{{ $trx->id }}" value="{{ $item['val'] }}" class="input-detail-lain w-full pl-7 pr-2 py-1 border border-gray-200 rounded focus:ring-1 focus:ring-blue-400 font-bold text-gray-700 text-xs">
                                            <input type="hidden" name="transaksi[{{ $trx->id }}][{{ $item['id'] }}]" id="asli_{{ $item['id'] }}_{{ $trx->id }}" value="{{ $item['val'] }}">
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                                
                                <div class="mt-4 pt-4 border-t-2 border-dashed border-blue-200 flex justify-between items-end">
                                    <span class="text-xs font-bold text-gray-500 uppercase tracking-widest">Subtotal Per Kendaraan</span>
                                    <span class="text-2xl font-black text-blue-700 tracking-tight" id="total_kalkulasi_{{ $trx->id }}">Rp 0</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>

                <!-- TOMBOL ACTION GLOBAL -->
                <div class="p-6 bg-white border-t border-gray-100 flex gap-3 justify-end rounded-b-xl">
                    <a href="{{ route('transactions.index') }}" class="px-6 py-2.5 bg-white border border-gray-300 hover:bg-gray-50 text-gray-700 font-bold rounded-lg transition-colors">
                        Batal
                    </a>
                    <button type="button" id="btnShowPreview" class="px-8 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-lg shadow-sm flex items-center gap-2 transition-colors">
                        <i class="fa-solid fa-magnifying-glass"></i> Preview Perubahan
                    </button>
                </div>
            </div>
        </form>
        <!-- ==================================================== -->
        <!-- MODAL PREVIEW INVOICE KOLEKTIF (EDIT)                -->
        <!-- ==================================================== -->
        <div id="previewModal" class="hidden fixed inset-0 z-50 bg-gray-900/70 backdrop-blur-sm overflow-y-auto flex items-center justify-center p-4 transition-opacity">
            <div class="bg-white shadow-2xl w-full max-w-5xl flex flex-col scale-100 max-h-[95vh] relative">
                
                <button type="button" id="btnCloseModal" class="absolute right-4 top-4 text-gray-400 hover:text-red-500 hover:bg-red-50 w-10 h-10 rounded-full flex items-center justify-center transition-colors z-10">
                    <i class="fa-solid fa-xmark text-2xl"></i>
                </button>

                <!-- KERTAS INVOICE (Isi Modal) -->
                <div class="p-10 overflow-y-auto bg-white flex-1 relative border-b-4 border-blue-600">
                    
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
                                <tr><td class="py-1 text-gray-600">No Invoice</td><td class="py-1 font-bold">: {{ $baseInvoice }}</td></tr>
                                <tr><td class="py-1 text-gray-600">Tgl. Pembuatan</td><td class="py-1 font-bold" id="prevInvDate">: -</td></tr>
                                <tr><td class="py-1 text-gray-600">Termin (hari)</td><td class="py-1 font-bold" id="prevTerminHari">: -</td></tr>
                            </table>
                        </div>
                    </div>

                    <div class="mb-6">
                        <p class="text-sm text-gray-700 mb-1">Kepada Yth :</p>
                        <h3 class="font-bold text-lg text-gray-900 uppercase">{{ $client->nama_lengkap ?? 'Tanpa Nama' }}</h3>
                        <p class="text-xs text-gray-700 mt-1">{{ $client->alamat ?? '-' }}</p>
                        <p class="text-xs text-gray-700 mt-1">No. WhatsApp / Telp: {{ $client->no_whatsapp ?? '-' }}</p>
                    </div>

                    <p class="text-sm text-gray-800 mb-2">Dengan ini, kami menyampaikan pengajuan atas <strong>Layanan Biro Jasa Kolektif</strong> <span class="float-right underline text-xs">Kendaraan Terlampir:</span></p>

                    <!-- TABEL KENDARAAN -->
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
                        <tbody id="previewTableBody"></tbody>
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

                    <!-- TERBILANG & CATATAN -->
                    <div class="mb-10 text-sm">
                        <div class="flex mb-2 items-center">
                            <div class="w-32 text-gray-700 uppercase font-bold text-xs tracking-wider">TERBILANG:</div>
                            <div class="font-bold italic flex-1 capitalize bg-gray-100 px-3 py-1.5 rounded" id="prevTerbilang">** Nol Rupiah **</div>
                        </div>
                        <div class="flex items-start mt-4">
                            <div class="w-32 text-gray-700 font-bold text-xs mt-1 uppercase tracking-wider">CATATAN:</div>
                            <div class="flex-1 bg-yellow-50 border border-yellow-200 px-3 py-2 text-xs text-gray-800 rounded" id="prevCatatan"></div>
                        </div>
                    </div>

                    <!-- RINCIAN BIAYA LAINNYA -->
                    <div class="flex justify-between items-start text-sm">
                        <div class="w-1/2">
                            <p class="font-bold text-gray-700 uppercase">Rincian Biaya Lainnya :</p>
                            <p class="text-xs text-gray-500 italic mt-1">Akumulasi total dari seluruh kendaraan di atas.</p>
                        </div>
                        <div class="w-1/2 flex justify-end">
                            <table class="w-[85%] border-collapse border border-gray-900 text-xs">
                                <tbody>
                                    <tr><td class="border border-gray-900 px-3 py-1.5">Loket Pendaftaran</td><td class="border border-gray-900 px-3 py-1.5 w-8">Rp</td><td class="border border-gray-900 px-3 py-1.5 text-right font-medium" id="prevPendaftaran">-</td></tr>
                                    <tr><td class="border border-gray-900 px-3 py-1.5">Loket Cek Fisik</td><td class="border border-gray-900 px-3 py-1.5">Rp</td><td class="border border-gray-900 px-3 py-1.5 text-right font-medium" id="prevCekFisik">-</td></tr>
                                    <tr><td class="border border-gray-900 px-3 py-1.5 transition-colors" id="prevLabelAcc">Acc Tidak Hadir STNK</td><td class="border border-gray-900 px-3 py-1.5">Rp</td><td class="border border-gray-900 px-3 py-1.5 text-right font-medium" id="prevAccTidakHadir">-</td></tr>
                                    <tr><td class="border border-gray-900 px-3 py-1.5">Acc Domisili</td><td class="border border-gray-900 px-3 py-1.5">Rp</td><td class="border border-gray-900 px-3 py-1.5 text-right font-medium" id="prevAccDomisili">-</td></tr>
                                    <tr><td class="border border-gray-900 px-3 py-1.5">Loket Penetapan</td><td class="border border-gray-900 px-3 py-1.5">Rp</td><td class="border border-gray-900 px-3 py-1.5 text-right font-medium" id="prevPenetapan">-</td></tr>
                                    <tr><td class="border border-gray-900 px-3 py-1.5">Loket Pengesahan 1</td><td class="border border-gray-900 px-3 py-1.5">Rp</td><td class="border border-gray-900 px-3 py-1.5 text-right font-medium" id="prevPengesahan1">-</td></tr>
                                    <tr><td class="border border-gray-900 px-3 py-1.5">Loket Pengesahan 2</td><td class="border border-gray-900 px-3 py-1.5">Rp</td><td class="border border-gray-900 px-3 py-1.5 text-right font-medium" id="prevPengesahan2">-</td></tr>
                                    <tr><td class="border border-gray-900 px-3 py-1.5">Bea Materai</td><td class="border border-gray-900 px-3 py-1.5">Rp</td><td class="border border-gray-900 px-3 py-1.5 text-right font-medium" id="prevMaterai">-</td></tr>
                                    <tr class="font-bold bg-gray-100"><td class="border border-gray-900 px-3 py-2 text-right">Total Biaya Lain</td><td class="border border-gray-900 px-3 py-2">Rp</td><td class="border border-gray-900 px-3 py-2 text-right" id="prevTotalLainBawah">-</td></tr>
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
                    <button type="button" id="btnSubmitReal" class="px-8 py-2.5 bg-yellow-500 hover:bg-yellow-600 text-white font-bold rounded-lg shadow-sm flex items-center gap-2 transition-colors">
                        <i class="fa-solid fa-floppy-disk"></i> Konfirmasi & Simpan
                    </button>
                </div>
            </div>
        </div>

    </div>

    <!-- SCRIPT UNTUK FORMAT RUPIAH OTOMATIS & LABEL DINAMIS -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const formatRupiah = (angka) => new Intl.NumberFormat('id-ID').format(angka || 0);

            const trxCards = document.querySelectorAll('.trx-card');

            trxCards.forEach(card => {
                const tid = card.dataset.tid; 
                
                const pajakTampil = document.getElementById(`pajak_tampil_${tid}`);
                const pajakAsli = document.getElementById(`pajak_asli_${tid}`);
                const jasaTampil = document.getElementById(`jasa_tampil_${tid}`);
                const jasaAsli = document.getElementById(`jasa_asli_${tid}`);
                const labelTotal = document.getElementById(`total_kalkulasi_${tid}`);
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
                        
                        if(document.activeElement !== inputTampil) {
                            inputTampil.value = nilai === 0 ? '' : formatRupiah(nilai);
                        }
                    });

                    if(document.activeElement !== pajakTampil) pajakTampil.value = totalPajak === 0 ? '' : formatRupiah(totalPajak);
                    if(document.activeElement !== jasaTampil) jasaTampil.value = totalJasa === 0 ? '' : formatRupiah(totalJasa);
                    
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

                // Jalankan hitungan pertama kali agar data database langsung berformat Rupiah
                hitungTotal();
            });

            // =======================================================
            // LOGIKA PERUBAHAN LABEL ACC TIDAK HADIR
            // =======================================================
            const selectLayananElems = document.querySelectorAll('.select-layanan');
            
            const updateLabelAcc = (selectElem) => {
                const vid = selectElem.dataset.vid; // ID Transaksi
                const labelAcc = document.getElementById(`label_acc_tidak_hadir_${vid}`);
                
                if (labelAcc) {
                    if (selectElem.value === 'Pajak 5 Tahunan') {
                        labelAcc.innerText = 'Acc Tdk Hadir Unit';
                        labelAcc.classList.add('text-blue-600', 'font-black');
                    } else {
                        labelAcc.innerText = 'Acc Tdk Hadir STNK';
                        labelAcc.classList.remove('text-blue-600', 'font-black');
                    }
                }
            };

            selectLayananElems.forEach(select => {
                // Jalankan saat halaman edit dimuat pertama kali (menyesuaikan data database)
                updateLabelAcc(select);
                
                // Jalankan saat opsi diganti oleh admin
                select.addEventListener('change', function() {
                    updateLabelAcc(this);
                });
            });

            // ==============================================================
            // LOGIKA POP-UP PREVIEW INVOICE EDIT (TERBILANG & KALKULASI)
            // ==============================================================
            
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
                    let has5Tahunan = false;

                    // Iterasi semua card karena ini form edit (semua kendaraan diproses)
                    document.querySelectorAll('.trx-card').forEach(card => {
                        const vid = card.dataset.tid;
                        selectedCount++;
                        
                        // 1. Ambil Nama Kendaraan & Layanan
                        const nopolText = card.querySelector('input[disabled]').value;
                        const splitText = nopolText.split(' - ');
                        const nopol = splitText[0];
                        const merk = splitText[1] || '-';

                        const layananElem = card.querySelector(`select[name="transaksi[${vid}][jenis_layanan]"]`);
                        const layanan = layananElem.options[layananElem.selectedIndex].text;
                        
                        if (layananElem.value === 'Pajak 5 Tahunan') has5Tahunan = true;
                        
                        // 2. Ambil Biaya
                        const pajak = parseInt(document.getElementById(`pajak_asli_${vid}`).value) || 0;
                        const jasa  = parseInt(document.getElementById(`jasa_asli_${vid}`).value) || 0;
                        
                        const pend = parseInt(document.getElementById(`asli_loket_pendaftaran_${vid}`).value) || 0;
                        const cekf = parseInt(document.getElementById(`asli_loket_cek_fisik_${vid}`).value) || 0;
                        const acc  = parseInt(document.getElementById(`asli_acc_tidak_hadir_${vid}`).value) || 0;
                        const dom  = parseInt(document.getElementById(`asli_acc_domisili_${vid}`).value) || 0;
                        const pntp = parseInt(document.getElementById(`asli_loket_penetapan_${vid}`).value) || 0;
                        const psh1 = parseInt(document.getElementById(`asli_loket_pengesahan_1_${vid}`).value) || 0;
                        const psh2 = parseInt(document.getElementById(`asli_loket_pengesahan_2_${vid}`).value) || 0;
                        const mtr  = parseInt(document.getElementById(`asli_bea_materai_${vid}`).value) || 0;

                        const subLain = pend + cekf + acc + dom + pntp + psh1 + psh2 + mtr;
                        const subTotal = pajak + jasa + subLain;

                        sumPendaftaran += pend; sumCekFisik += cekf; sumAccTidakHadir += acc; sumAccDomisili += dom;
                        sumPenetapan += pntp; sumPengesahan1 += psh1; sumPengesahan2 += psh2; sumMaterai += mtr;
                        grandPajak += pajak; grandJasa += jasa; grandLain += subLain; grandSemua += subTotal;

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
                    });

                    // Tempelkan data ke dalam Modal
                    document.getElementById('previewTableBody').innerHTML = htmlContent;
                    
                    const inputTglMasuk = document.querySelector('input[name="tgl_masuk"]');
                    const formatTgl = (tglStr) => tglStr ? tglStr.split('-').reverse().join('/') : '-';
                    document.getElementById('prevInvDate').innerText = `: ${formatTgl(inputTglMasuk.value)}`;

                    // LOGIKA TANGGAL & CATATAN
                    const tglMulaiVal = document.querySelector('input[name="tgl_mulai_termin"]').value;
                    const tglJatuhVal = document.querySelector('input[name="tgl_jatuh_tempo"]').value;
                    
                    if(tglMulaiVal && tglJatuhVal) {
                        const diffTime = new Date(tglJatuhVal) - new Date(tglMulaiVal);
                        const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));
                        
                        if (diffDays >= 0) {
                            document.getElementById('prevTerminHari').innerText = ': ' + diffDays;
                            document.getElementById('prevCatatan').innerHTML = `Pembayaran tagihan ini memiliki termin <strong>${diffDays} Hari</strong> terhitung sejak tanggal berkas diterima (${formatTgl(tglMulaiVal)}). Jatuh tempo pembayaran pada tanggal <strong class="text-red-600">${formatTgl(tglJatuhVal)}</strong>.`;
                        } else {
                            document.getElementById('prevTerminHari').innerText = ': -';
                            document.getElementById('prevCatatan').innerHTML = `<span class="text-red-500 italic">Tanggal Jatuh Tempo tidak boleh mundur!</span>`;
                        }
                    }

                    // Set Total
                    document.getElementById('prevGrandTotalPajak').innerText = formatRupiah(grandPajak);
                    document.getElementById('prevGrandTotalJasa').innerText = formatRupiah(grandJasa);
                    document.getElementById('prevGrandTotalLain').innerText = formatRupiah(grandLain);
                    document.getElementById('prevGrandTotalBiaya').innerText = formatRupiah(grandSemua);
                    document.getElementById('prevTotalLainBawah').innerText = formatRupiah(grandLain);
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

                    const prevLabelAcc = document.getElementById('prevLabelAcc');
                    if (has5Tahunan) {
                        prevLabelAcc.innerText = 'Acc Tidak Hadir Unit';
                        prevLabelAcc.classList
                    } else {
                        prevLabelAcc.innerText = 'Acc Tidak Hadir STNK';
                        prevLabelAcc.classList
                    }

                    previewModal.classList.remove('hidden');
                });
            }

            // Aksi Tutup Modal
            const closeModal = () => document.getElementById('previewModal').classList.add('hidden');
            document.getElementById('btnCloseModal')?.addEventListener('click', closeModal);
            document.getElementById('btnCancelModal')?.addEventListener('click', closeModal);

            // Aksi Simpan Nyata
            document.getElementById('btnSubmitReal')?.addEventListener('click', function() {
                // Tampilkan loading (opsional) dan Submit form asli
                this.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Menyimpan...';
                this.classList.add('opacity-75', 'cursor-not-allowed');
                document.getElementById('editForm').submit();
            });


        });
    </script>
@endsection