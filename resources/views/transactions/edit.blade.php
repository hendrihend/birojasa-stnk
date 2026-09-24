@extends('layouts.app')

@section('title', 'Edit Transaksi')
@section('header_title', 'Edit Transaksi')

@section('content')
    <a href="{{ route('transactions.index') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-white border border-gray-300 rounded-lg text-gray-700 font-bold hover:bg-gray-50 transition-colors mb-6 shadow-sm">
        <i class="fa-solid fa-arrow-left"></i> Kembali ke Data Transaksi
    </a>

<div class="max-w-6xl mx-auto space-y-6">
    
    @if($errors->any())
        <div class="px-4 py-3 bg-red-50 border-l-4 border-red-500 text-red-700 rounded shadow-sm">
            <ul class="list-disc list-inside text-sm font-medium">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- ==================================================== -->
    <!-- INFO KLIEN -->
    <!-- ==================================================== -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 flex justify-between items-center">
        <div>
            <h2 class="text-xl text-gray-800">Edit Invoice: {{ $baseInvoice }}</h2>
            <p class="text-sm text-gray-500 font-medium mt-1">Klien: <span class="text-gray-800 font-bold">{{ $client->nama_lengkap ?? 'Tidak Diketahui' }}</span> ({{ $client->no_whatsapp ?? '-' }})</p>
        </div>
    </div>

    <!-- ==================================================== -->
    <!-- TAHAP 2: DAFTAR KENDARAAN (FORM EDIT) -->
    <!-- ==================================================== -->
    <form action="{{ route('transactions.update', $transactions->first()->id) }}" method="POST" id="collectiveForm">
        @csrf
        @method('PUT')
        
        @php
            $firstTrx = $transactions->first();
            $rincianGlobal = [
                ['id' => 'loket_pendaftaran', 'label' => 'Loket Pendaftaran', 'val' => $firstTrx->loket_pendaftaran],
                ['id' => 'loket_cek_fisik', 'label' => 'Cek Fisik', 'val' => $firstTrx->loket_cek_fisik],
                ['id' => 'acc_tidak_hadir', 'label' => 'Acc Tdk Hadir STNK', 'val' => $firstTrx->acc_tidak_hadir],
                ['id' => 'acc_domisili', 'label' => 'Acc Domisili', 'val' => $firstTrx->acc_domisili],
                ['id' => 'loket_penetapan', 'label' => 'Penetapan', 'val' => $firstTrx->loket_penetapan],
                ['id' => 'loket_pengesahan_1', 'label' => 'Pengesahan 1', 'val' => $firstTrx->loket_pengesahan_1],
                ['id' => 'loket_pengesahan_2', 'label' => 'Pengesahan 2', 'val' => $firstTrx->loket_pengesahan_2],
                ['id' => 'bea_materai', 'label' => 'Bea Materai', 'val' => $firstTrx->bea_materai],
            ];
        @endphp

        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <!-- HEADER GLOBAL -->
            <div class="p-6 border-b border-gray-100 bg-gray-50/50 flex flex-col md:flex-row justify-between gap-4 md:items-center">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-full bg-blue-100 flex items-center justify-center text-blue-600 text-lg">
                        <i class="fa-solid fa-file-signature"></i>
                    </div>
                    <div>
                        <h2 class="text-lg font-bold text-gray-800">Ubah Data Invoice Kolektif</h2>
                        <p class="text-xs text-gray-500">Ubah kendaraan yang diurus, edit rincian biaya, atau perbarui status layanan.</p>
                    </div>
                </div>
                
                <!-- Tanggal Masuk & Termin (Global untuk 1 Invoice) -->
                <div class="flex flex-wrap gap-3 w-full md:w-auto">
                    <div>
                        <input type="hidden" name="tgl_masuk" value="{{ $firstTrx->tgl_masuk }}" required class="w-full px-3 py-1.5 border border-gray-300 rounded focus:ring-1 outline-none text-sm">
                    </div>
                    <div>
                        <label class="block text-[11px] font-bold text-gray-700 mb-1">Dari Tgl (Mulai Termin) *</label>
                        <input type="date" name="tgl_mulai_termin" id="tgl_mulai_termin" value="{{ $firstTrx->tgl_mulai_termin }}" class="w-full px-3 py-1.5 border border-gray-300 rounded focus:ring-1 outline-none text-sm text-blue-700" required>
                    </div>
                    <div>
                        <label class="block text-[11px] font-bold text-gray-700 mb-1">Sampai Tgl (Jatuh Tempo) *</label>
                        <input type="date" name="tgl_jatuh_tempo" id="tgl_jatuh_tempo" value="{{ $firstTrx->tgl_jatuh_tempo }}" class="w-full px-3 py-1.5 border border-gray-300 rounded focus:ring-1 outline-none text-sm text-red-600" required>
                    </div>
                </div>
            </div>

            <!-- LOOPING KENDARAAN KLIEN -->
            <div class="p-6 space-y-8">
                <!-- BIAYA LAINNYA GLOBAL (SATU KALI INPUT) -->
                <div class="p-6 bg-blue-50/50 border-t border-gray-100">
                    <h3 class="text-sm font-bold text-gray-700 mb-4 uppercase tracking-wider"><i class="fa-solid fa-file-invoice-dollar text-blue-600 mr-2"></i> Rincian Biaya Lainnya</h3>
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 bg-white p-5 rounded-xl border border-blue-100 shadow-sm">
                        @foreach ($rincianGlobal as $item)
                        <div>
                            <label id="label_global_{{ $item['id'] }}" class="block text-xs font-bold text-gray-700 mb-1">{{ $item['label'] }}</label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-3 flex items-center text-gray-400 font-bold text-[11px]">Rp</span>
                                <input type="text" id="tampil_global_{{ $item['id'] }}" value="{{ $item['val'] > 0 ? number_format($item['val'], 0, ',', '.') : '' }}" placeholder="0" class="input-global-lain w-full pl-8 pr-3 py-1.5 border border-gray-300 rounded focus:ring-1 focus:ring-blue-500 font-bold text-gray-800 text-sm">
                                <input type="hidden" name="global_biaya[{{ $item['id'] }}]" id="asli_global_{{ $item['id'] }}" value="{{ $item['val'] }}">
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>

                <!-- TABEL ITEM DATA KENDARAAN -->
                <div class="p-6 bg-blue-50/50 border-t border-gray-100">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="font-bold text-sm">Tabel Item Data Kendaraan</h3>
                        <button type="button" id="btnAddRow" class="px-4 py-1.5 bg-gray-800 text-white text-xs font-bold rounded-lg hover:bg-gray-900 transition-colors whitespace-nowrap">
                            + Tambah Baris
                        </button>
                    </div>
                    <!-- Wadah untuk baris dinamis -->
                    <div id="dynamicVehicleContainer" class="space-y-2">
                        @foreach ($transactions as $index => $trx)
                        <div class="vehicle-row p-4 rounded-lg border border-blue-100 grid grid-cols-1 md:grid-cols-4 gap-4 relative" data-index="{{ $index }}">
                            
                            <!-- ID TRANSAKSI LAMA (PENTING) -->
                            <input type="hidden" name="transaksi[{{ $index }}][id]" value="{{ $trx->id }}">

                            <button type="button" class="btn-delete-row absolute -top-2 -right-2 bg-red-600 w-6 h-6 rounded-full text-white text-xs flex items-center justify-center shadow-lg hover:bg-red-700" title="Hapus baris">
                                <i class="fa-solid fa-xmark"></i>
                            </button>

                            <!-- Pilih Kendaraan -->
                            <div class="md:col-span-2">
                                <label class="block text-[11px] uppercase font-bold text-gray-800 mb-1">Pilih Kendaraan *</label>
                                <select name="transaksi[{{ $index }}][vehicle_id]" required class="w-full pr-3 py-1.5 border border-gray-300 rounded focus:ring-1 focus:ring-blue-500 text-gray-800 text-sm">
                                    <option value="">-- Pilih Kendaraan --</option>
                                    @foreach ($vehicles as $v)
                                        <option value="{{ $v->id }}" {{ $v->id == $trx->vehicle_id ? 'selected' : '' }}>
                                            {{ $v->nopol }} - {{ $v->merk }} {{ $v->tipe }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Pilih Layanan & Status -->
                            <div class="md:col-span-2">
                                <div class="flex gap-2">
                                    <div class="w-1/2">
                                        <label class="block text-[11px] uppercase font-bold text-gray-800 mb-1">Layanan *</label>
                                        <select name="transaksi[{{ $index }}][jenis_layanan]" class="select-layanan w-full px-3 py-1.5 border border-gray-300 rounded focus:ring-1 focus:ring-blue-500 text-gray-800 text-sm">
                                            <option value="Pajak Tahunan" {{ $trx->jenis_layanan == 'Pajak Tahunan' ? 'selected' : '' }}>Pajak Tahunan</option>
                                            <option value="Pajak 5 Tahunan" {{ $trx->jenis_layanan == 'Pajak 5 Tahunan' ? 'selected' : '' }}>Pajak 5 Tahunan</option>
                                        </select>
                                    </div>
                                    <div class="w-1/2">
                                        <label class="block text-[11px] uppercase font-bold text-gray-800 mb-1">Status *</label>
                                        <select name="transaksi[{{ $index }}][status_proses]" class="w-full px-3 py-1.5 border border-gray-300 rounded focus:ring-1 focus:ring-blue-500 text-gray-800 text-sm font-bold {{ $trx->status_proses == 'Done' ? 'text-green-600' : '' }}">
                                            @foreach ($statuses as $st)
                                                <option value="{{ $st }}" {{ $trx->status_proses == $st ? 'selected' : '' }}>{{ $st }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <!-- Input Pajak -->
                            <div>
                                <label class="block text-[11px] uppercase font-bold text-gray-800 mb-1">Pajak</label>
                                <div class="relative">
                                    <span class="absolute inset-y-0 left-3 flex items-center text-gray-400 font-bold text-[11px]">Rp</span>
                                    <input type="text" class="input-uang w-full pl-8 pr-3 py-1.5 border border-gray-300 rounded focus:ring-1 focus:ring-blue-500 font-bold text-gray-800 text-sm" value="{{ $trx->biaya_pajak > 0 ? number_format($trx->biaya_pajak, 0, ',', '.') : '' }}" placeholder="0">
                                    <input type="hidden" name="transaksi[{{ $index }}][biaya_pajak]" class="asli-pajak" value="{{ $trx->biaya_pajak }}">
                                </div>
                            </div>

                            <!-- Input Jasa -->
                            <div>
                                <label class="block text-[11px] uppercase font-bold text-gray-800 mb-1">Jasa</label>
                                <div class="relative">
                                    <span class="absolute inset-y-0 left-3 flex items-center text-gray-400 font-bold text-[11px]">Rp</span>
                                    <input type="text" class="input-uang w-full pl-8 pr-3 py-1.5 border border-gray-300 rounded focus:ring-1 focus:ring-blue-500 font-bold text-gray-800 text-sm" value="{{ $trx->biaya_jasa > 0 ? number_format($trx->biaya_jasa, 0, ',', '.') : '' }}" placeholder="0">
                                    <input type="hidden" name="transaksi[{{ $index }}][biaya_jasa]" class="asli-jasa" value="{{ $trx->biaya_jasa }}">
                                </div>
                            </div>

                            <!-- Akumulasi Rincian Biaya Lainnya -->
                            <div>
                                <label class="block text-[11px] uppercase font-bold text-gray-800 mb-1">Lainnya</label>
                                <div class="relative">
                                    <span class="absolute inset-y-0 left-3 flex items-center text-gray-400 font-bold text-[11px]">Rp</span>
                                    <input type="text" class="tampil-lainnya w-full pl-8 pr-3 py-1.5 border border-gray-200 rounded bg-gray-50 text-gray-600 text-sm font-bold cursor-not-allowed outline-none" placeholder="0" readonly>
                                    <input type="hidden" name="transaksi[{{ $index }}][biaya_lain]" class="asli-lainnya" value="0">
                                </div>
                            </div>

                            <!-- Akumulasi Keseluruhan (Total Per Baris) -->
                            <div>
                                <label class="block text-[11px] uppercase font-bold text-gray-800 mb-1">Total</label>
                                <div class="relative">
                                    <span class="absolute inset-y-0 left-3 flex items-center text-gray-400 font-bold text-[11px]">Rp</span>
                                    <input type="text" class="tampil-total w-full pl-8 pr-3 py-1.5 border border-blue-200 rounded bg-blue-50 text-blue-700 text-sm font-bold cursor-not-allowed outline-none" placeholder="0" readonly>
                                    <input type="hidden" name="transaksi[{{ $index }}][total_biaya]" class="asli-total" value="0">
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    
                    <p id="emptyMessage" class="text-xs text-gray-500 text-center py-6 italic {{ $transactions->count() > 0 ? 'hidden' : '' }}">
                        Belum ada item kendaraan. Tambahkan baris baru.
                    </p>
                </div>
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
                            <tr><td class="py-1 text-gray-600">No Invoice</td><td class="py-1 font-bold">: {{ $baseInvoice }}</td></tr>
                            <tr><td class="py-1 text-gray-600">Tgl. Pembuatan</td><td class="py-1 font-bold" id="prevInvDate">: -</td></tr>
                            <tr><td class="py-1 text-gray-600">Termin (hari)</td><td class="py-1 font-bold" id="prevTerminHari">: -</td></tr>
                        </table>
                    </div>
                </div>

                <!-- INFO KEPADA YTH -->
                <div class="mb-6">
                    <p class="text-sm text-gray-700 mb-1">Kepada Yth :</p>
                    <h3 class="font-bold text-lg text-gray-900 uppercase">{{ $client->nama_lengkap ?? '' }}</h3>
                    <p class="text-xs text-gray-700 mt-1">{{ $client->alamat ?? '-' }}</p>
                    <p class="text-xs text-gray-700 mt-1">No. WhatsApp / Telp: {{ $client->no_whatsapp ?? '-' }}</p>
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
                    Tutup Preview
                </button>
                <!-- Tombol Submit juga ada di modal untuk kemudahan -->
                <button type="button" id="btnSubmitReal" onclick="document.getElementById('btnSubmitReal').click()" class="px-8 py-2.5 bg-green-600 hover:bg-green-700 text-white font-bold rounded-lg shadow-sm transition-colors flex items-center gap-2">
                    <i class="fa-solid fa-save"></i> Perbarui Data
                </button>
            </div>
        </div>
    </div>
</div>

<!-- SCRIPT UNTUK FORMAT RUPIAH & HITUNG DINAMIS -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        
        // 1. Ambil data kendaraan & set Index
        const listKendaraan = @json($vehicles);
        const listStatuses = @json($statuses); // Menarik array ['Pending', 'Done', 'Cancel']
        let rowIndex = {{ $transactions->count() }}; 

        const btnAddRow = document.getElementById('btnAddRow');
        const container = document.getElementById('dynamicVehicleContainer');
        const emptyMessage = document.getElementById('emptyMessage');

        // Fungsi Membuat Dropdown Options Kendaraan
        const buatOptionsKendaraan = () => {
            let options = '<option value="">-- Pilih Kendaraan --</option>';
            listKendaraan.forEach(v => {
                options += `<option value="${v.id}">${v.nopol} - ${v.merk} ${v.tipe}</option>`;
            });
            return options;
        };

        // Fungsi Membuat Dropdown Options Status
        const buatOptionsStatus = () => {
            let options = '';
            listStatuses.forEach(st => {
                options += `<option value="${st}">${st}</option>`;
            });
            return options;
        };

        const pasangEventPadaBaris = (barisElemen) => {
            // A. Aksi Tombol Hapus Baris
            const btnDelete = barisElemen.querySelector('.btn-delete-row');
            btnDelete.addEventListener('click', function() {
                barisElemen.remove(); 
                
                if (container.querySelectorAll('.vehicle-row').length === 0) {
                    if (emptyMessage) emptyMessage.style.display = 'block';
                }
                
                if (typeof checkGlobalAccLabel === 'function') checkGlobalAccLabel();
                hitungUlangSemuaBaris();
            });

            // B. Format Rupiah untuk Input Pajak & Jasa
            const inputUangList = barisElemen.querySelectorAll('.input-uang');
            inputUangList.forEach(inputTampil => {
                const inputAsli = inputTampil.nextElementSibling;
                
                inputTampil.addEventListener('input', function(e) {
                    let angkaMurni = this.value.replace(/[^0-9]/g, '');
                    inputAsli.value = angkaMurni || 0;
                    this.value = angkaMurni ? formatRupiah(angkaMurni) : '';
                    hitungUlangSemuaBaris();
                });
            });

            // C. Trigger Update Label ACC saat dropdown layanan diganti
            const selectLayanan = barisElemen.querySelector('.select-layanan');
            if (selectLayanan) {
                selectLayanan.addEventListener('change', function() {
                    if (typeof checkGlobalAccLabel === 'function') checkGlobalAccLabel();
                });
            }
        };

        // Aksi ketika tombol "Tambah Baris" diklik
        btnAddRow.addEventListener('click', function() {
            if(emptyMessage) emptyMessage.style.display = 'none';

            const barisBaru = document.createElement('div');
            barisBaru.className = 'vehicle-row p-4 rounded-lg border border-blue-100 grid grid-cols-1 md:grid-cols-4 gap-4 relative';
            barisBaru.dataset.index = rowIndex;

            barisBaru.innerHTML = `
                <button type="button" class="btn-delete-row absolute -top-2 -right-2 bg-red-600 w-6 h-6 rounded-full text-white text-xs flex items-center justify-center shadow-lg hover:bg-red-700" title="Hapus baris">
                    <i class="fa-solid fa-xmark"></i>
                </button>

                <!-- Pilih Kendaraan -->
                <div class="md:col-span-2">
                    <label class="block text-[11px] uppercase font-bold text-gray-800 mb-1">Pilih Kendaraan *</label>
                    <select name="transaksi[${rowIndex}][vehicle_id]" required class="w-full px-3 py-1.5 border border-gray-300 rounded focus:ring-1 focus:ring-blue-500 text-gray-800 text-sm">
                        ${buatOptionsKendaraan()}
                    </select>
                </div>

                <!-- Pilih Layanan & Status -->
                <div class="md:col-span-2">
                    <div class="flex gap-2">
                        <div class="w-1/2">
                            <label class="block text-[11px] uppercase font-bold text-gray-800 mb-1">Layanan *</label>
                            <select name="transaksi[${rowIndex}][jenis_layanan]" class="select-layanan w-full px-3 py-1.5 border border-gray-300 rounded focus:ring-1 focus:ring-blue-500 text-gray-800 text-sm">
                                <option value="Pajak Tahunan">Pajak Tahunan</option>
                                <option value="Pajak 5 Tahunan">Pajak 5 Tahunan</option>
                            </select>
                        </div>
                        <div class="w-1/2">
                            <label class="block text-[11px] uppercase font-bold text-gray-800 mb-1">Status *</label>
                            <select name="transaksi[${rowIndex}][status_proses]" class="w-full px-3 py-1.5 border border-gray-300 rounded focus:ring-1 focus:ring-blue-500 text-gray-800 text-sm">
                                ${buatOptionsStatus()}
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Input Pajak -->
                <div>
                    <label class="block text-[11px] uppercase font-bold text-gray-800 mb-1">Pajak</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-3 flex items-center text-gray-400 font-bold text-[11px]">Rp</span>
                        <input type="text" class="input-uang w-full pl-8 pr-3 py-1.5 border border-gray-300 rounded focus:ring-1 focus:ring-blue-500 font-bold text-gray-800 text-sm" placeholder="0">
                        <input type="hidden" name="transaksi[${rowIndex}][biaya_pajak]" class="asli-pajak" value="0">
                    </div>
                </div>

                <!-- Input Jasa -->
                <div>
                    <label class="block text-[11px] uppercase font-bold text-gray-800 mb-1">Jasa</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-3 flex items-center text-gray-400 font-bold text-[11px]">Rp</span>
                        <input type="text" class="input-uang w-full pl-8 pr-3 py-1.5 border border-gray-300 rounded focus:ring-1 focus:ring-blue-500 font-bold text-gray-800 text-sm" placeholder="0">
                        <input type="hidden" name="transaksi[${rowIndex}][biaya_jasa]" class="asli-jasa" value="0">
                    </div>
                </div>

                <!-- Akumulasi Rincian Biaya Lainnya -->
                <div>
                    <label class="block text-[11px] uppercase font-bold text-gray-800 mb-1">Lainnya</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-3 flex items-center text-gray-400 font-bold text-[11px]">Rp</span>
                        <input type="text" class="tampil-lainnya w-full pl-8 pr-3 py-1.5 border border-gray-200 rounded bg-gray-50 text-gray-600 text-sm font-bold cursor-not-allowed outline-none" placeholder="0" readonly>
                        <input type="hidden" name="transaksi[${rowIndex}][biaya_lain]" class="asli-lainnya" value="0">
                    </div>
                </div>

                <!-- Akumulasi Keseluruhan (Total Per Baris) -->
                <div>
                    <label class="block text-[11px] uppercase font-bold text-gray-800 mb-1">Total</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-3 flex items-center text-gray-400 font-bold text-[11px]">Rp</span>
                        <input type="text" class="tampil-total w-full pl-8 pr-3 py-1.5 border border-blue-200 rounded bg-blue-50 text-blue-700 text-sm font-bold cursor-not-allowed outline-none" placeholder="0" readonly>
                        <input type="hidden" name="transaksi[${rowIndex}][total_biaya]" class="asli-total" value="0">
                    </div>
                </div>
            `;

            container.appendChild(barisBaru);
            pasangEventPadaBaris(barisBaru);
            rowIndex++; 
            hitungUlangSemuaBaris();
        });

        // Fungsi Utama Hitung Ulang Semua Baris Real-Time
        const hitungUlangSemuaBaris = () => {
            let totalGlobal = 0;
            document.querySelectorAll('.input-global-lain').forEach(inputTampil => {
                let idAsli = inputTampil.id.replace('tampil_global_', 'asli_global_');
                let nilaiAsli = parseInt(document.getElementById(idAsli).value) || 0;
                totalGlobal += nilaiAsli;
            });

            document.querySelectorAll('.vehicle-row').forEach(row => {
                const pajak = parseInt(row.querySelector('.asli-pajak').value) || 0;
                const jasa = parseInt(row.querySelector('.asli-jasa').value) || 0;

                const tampilLainnya = row.querySelector('.tampil-lainnya');
                const asliLainnya = row.querySelector('.asli-lainnya');
                if (asliLainnya && tampilLainnya) {
                    asliLainnya.value = totalGlobal;
                    tampilLainnya.value = totalGlobal > 0 ? formatRupiah(totalGlobal) : '';
                }

                const totalBaris = pajak + jasa + totalGlobal;
                const tampilTotal = row.querySelector('.tampil-total');
                const asliTotal = row.querySelector('.asli-total');
                if (asliTotal && tampilTotal) {
                    asliTotal.value = totalBaris;
                    tampilTotal.value = totalBaris > 0 ? formatRupiah(totalBaris) : '';
                }
            });
        };

        const formatRupiah = (angka) => new Intl.NumberFormat('id-ID').format(angka || 0);
        const formatTgl = (tglStr) => tglStr ? tglStr.split('-').reverse().join('/') : '-';

        // FORMAT RUPIAH BIAYA GLOBAL
        const inputGlobalTampil = document.querySelectorAll('.input-global-lain');
        inputGlobalTampil.forEach(tampil => {
            let idAsli = tampil.id.replace('tampil_global_', 'asli_global_');
            let asli = document.getElementById(idAsli);
            
            tampil.addEventListener('input', function(e) {
                let angkaMurni = this.value.replace(/[^0-9]/g, '');
                asli.value = angkaMurni || 0;
                this.value = angkaMurni ? formatRupiah(angkaMurni) : '';
                hitungUlangSemuaBaris();
            });
        });

        const checkGlobalAccLabel = () => {
            let has5Tahunan = false;
            document.querySelectorAll('.vehicle-row').forEach(row => {
                const selectKendaraan = row.querySelector('select[name$="[vehicle_id]"]');
                if (selectKendaraan && selectKendaraan.value !== "") {
                    const layanan = row.querySelector('.select-layanan').value;
                    if (layanan === 'Pajak 5 Tahunan') has5Tahunan = true;
                }
            });
            
            const labelAccGlobal = document.getElementById('label_global_acc_tidak_hadir');
            if (labelAccGlobal) {
                labelAccGlobal.innerText = has5Tahunan ? 'Acc Tidak Hadir Unit' : 'Acc Tidak Hadir STNK';
            }
        };

        // ==============================================================
        // LOGIKA POP-UP PREVIEW INVOICE
        // ==============================================================
        const previewTableBody = document.getElementById('previewTableBody');
        
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
                
                const g_pnd  = parseInt(document.getElementById('asli_global_loket_pendaftaran').value) || 0;
                const g_cf   = parseInt(document.getElementById('asli_global_loket_cek_fisik').value) || 0;
                const g_acc  = parseInt(document.getElementById('asli_global_acc_tidak_hadir').value) || 0;
                const g_dom  = parseInt(document.getElementById('asli_global_acc_domisili').value) || 0;
                const g_pntp = parseInt(document.getElementById('asli_global_loket_penetapan').value) || 0;
                const g_psh1 = parseInt(document.getElementById('asli_global_loket_pengesahan_1').value) || 0;
                const g_psh2 = parseInt(document.getElementById('asli_global_loket_pengesahan_2').value) || 0;
                const g_mtr  = parseInt(document.getElementById('asli_global_bea_materai').value) || 0;
                
                const subLainPerKendaraan = g_pnd + g_cf + g_acc + g_dom + g_pntp + g_psh1 + g_psh2 + g_mtr;

                document.querySelectorAll('.vehicle-row').forEach(row => {
                    const selectKendaraan = row.querySelector('select[name$="[vehicle_id]"]');
                    
                    if (selectKendaraan && selectKendaraan.value !== "") {
                        selectedCount++;
                        
                        const nopolText = selectKendaraan.options[selectKendaraan.selectedIndex].text;
                        const splitText = nopolText.split(' - ');
                        const nopol = splitText[0];
                        const merk = splitText[1] || '-';

                        const layananElem = row.querySelector('.select-layanan');
                        const layanan = layananElem.options[layananElem.selectedIndex].text;
                        
                        if (layananElem.value === 'Pajak 5 Tahunan') has5Tahunan = true;
                        
                        const pajak = parseInt(row.querySelector('.asli-pajak').value) || 0;
                        const jasa = parseInt(row.querySelector('.asli-jasa').value) || 0;
                        
                        const subTotal = pajak + jasa + subLainPerKendaraan;

                        sumPendaftaran += g_pnd; sumCekFisik += g_cf; sumAccTidakHadir += g_acc; sumAccDomisili += g_dom;
                        sumPenetapan += g_pntp; sumPengesahan1 += g_psh1; sumPengesahan2 += g_psh2; sumMaterai += g_mtr;

                        grandPajak += pajak; grandJasa += jasa; grandLain += subLainPerKendaraan; grandSemua += subTotal;

                        htmlContent += `
                            <tr>
                                <td class="border border-gray-900 py-2 px-2 text-center">${selectedCount}</td>
                                <td class="border border-gray-900 py-2 px-2 text-center text-xs font-bold">${nopol}</td>
                                <td class="border border-gray-900 py-2 px-2 text-center text-xs">${merk}</td>
                                <td class="border border-gray-900 py-2 px-2 text-center text-xs">${layanan}</td>
                                <td class="border border-gray-900 py-2 px-2 text-right text-xs">${formatRupiah(pajak)}</td>
                                <td class="border border-gray-900 py-2 px-2 text-right text-xs">${formatRupiah(jasa)}</td>
                                <td class="border border-gray-900 py-2 px-2 text-right text-xs">${formatRupiah(subLainPerKendaraan)}</td>
                                <td class="border border-gray-900 py-2 px-2 text-right font-bold flex justify-between text-xs">
                                    <span>Rp</span> <span>${formatRupiah(subTotal)}</span>
                                </td>
                            </tr>
                        `;
                    }
                });

                if (selectedCount === 0) {
                    alert('Silakan tambahkan baris dan pilih minimal 1 kendaraan yang ingin diproses!');
                    return;
                }

                previewTableBody.innerHTML = htmlContent;
                
                const inputTglMasuk = document.querySelector('input[name="tgl_masuk"]');
                document.getElementById('prevInvDate').innerText = `: ${formatTgl(inputTglMasuk.value)}`;

                const tglMulaiVal = document.getElementById('tgl_mulai_termin').value;
                const tglJatuhVal = document.getElementById('tgl_jatuh_tempo').value;
                const prevTerminHari = document.getElementById('prevTerminHari');
                const prevCatatan = document.getElementById('prevCatatan');
                
                if(tglMulaiVal && tglJatuhVal) {
                    const date1 = new Date(tglMulaiVal);
                    const date2 = new Date(tglJatuhVal);
                    const diffDays = Math.ceil((date2 - date1) / (1000 * 60 * 60 * 24));
                    
                    if (diffDays >= 0) {
                        if(prevTerminHari) prevTerminHari.innerText = ': ' + diffDays;
                        if(prevCatatan) prevCatatan.innerHTML = `Pembayaran tagihan ini memiliki termin <strong>${diffDays} Hari</strong> terhitung sejak tanggal berkas diterima (${formatTgl(tglMulaiVal)}). Jatuh tempo pembayaran pada tanggal <strong class="text-red-600">${formatTgl(tglJatuhVal)}</strong>.`;
                    } else {
                        if(prevTerminHari) prevTerminHari.innerText = ': -';
                        if(prevCatatan) prevCatatan.innerHTML = `<span class="text-red-500 italic"><i class="fa-solid fa-triangle-exclamation"></i> Tanggal Jatuh Tempo tidak boleh mundur!</span>`;
                    }
                } else {
                    if(prevTerminHari) prevTerminHari.innerText = ': -';
                    if(prevCatatan) prevCatatan.innerHTML = `<span class="text-red-500 italic">Silakan isi tanggal mulai dan jatuh tempo di form!</span>`;
                }

                document.getElementById('prevGrandTotalPajak').innerText = formatRupiah(grandPajak);
                document.getElementById('prevGrandTotalJasa').innerText = formatRupiah(grandJasa);
                document.getElementById('prevGrandTotalLain').innerText = formatRupiah(grandLain);
                document.getElementById('prevGrandTotalBiaya').innerText = formatRupiah(grandSemua);
                document.getElementById('prevTotalLainBawah').innerText = formatRupiah(grandLain);
                document.getElementById('prevTerbilang').innerText = '** ' + terbilangJS(grandSemua) + ' **';

                document.getElementById('prevPendaftaran').innerText = sumPendaftaran > 0 ? formatRupiah(sumPendaftaran) : '-';
                document.getElementById('prevCekFisik').innerText = sumCekFisik > 0 ? formatRupiah(sumCekFisik) : '-';
                document.getElementById('prevAccTidakHadir').innerText = sumAccTidakHadir > 0 ? formatRupiah(sumAccTidakHadir) : '-';
                document.getElementById('prevAccDomisili').innerText = sumAccDomisili > 0 ? formatRupiah(sumAccDomisili) : '-';
                document.getElementById('prevPenetapan').innerText = sumPenetapan > 0 ? formatRupiah(sumPenetapan) : '-';
                document.getElementById('prevPengesahan1').innerText = sumPengesahan1 > 0 ? formatRupiah(sumPengesahan1) : '-';
                document.getElementById('prevPengesahan2').innerText = sumPengesahan2 > 0 ? formatRupiah(sumPengesahan2) : '-';
                document.getElementById('prevMaterai').innerText = sumMaterai > 0 ? formatRupiah(sumMaterai) : '-';

                const prevLabelAcc = document.getElementById('prevLabelAcc');
                if(prevLabelAcc) {
                    if (has5Tahunan) {
                        prevLabelAcc.innerText = 'Acc Tidak Hadir Unit';
                    } else {
                        prevLabelAcc.innerText = 'Acc Tidak Hadir STNK';
                    }
                }

                previewModal.classList.remove('hidden');
            });
        }

        const closeModal = () => {
            if(previewModal) previewModal.classList.add('hidden');
        };
        document.getElementById('btnCloseModal')?.addEventListener('click', closeModal);
        document.getElementById('btnCancelModal')?.addEventListener('click', closeModal);

        document.getElementById('btnSubmitReal')?.addEventListener('click', function(e) {
            if(container.querySelectorAll('.vehicle-row').length === 0) {
                e.preventDefault();
                alert('Silakan tambahkan baris dan pilih minimal 1 kendaraan!');
                return;
            }
            this.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Menyimpan...';
            this.disabled = true;
            document.getElementById('collectiveForm').submit();
        });

        // ==============================================================
        // INISIALISASI SAAT HALAMAN EDIT DIMUAT
        // ==============================================================
        document.querySelectorAll('.vehicle-row').forEach(row => {
            pasangEventPadaBaris(row);
        });
        checkGlobalAccLabel();
        hitungUlangSemuaBaris();
    });
</script>

@endsection