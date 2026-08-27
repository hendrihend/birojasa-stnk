@extends('layouts.app')

@section('title', 'Buat Transaksi Baru')
@section('header_title', 'Mulai Pengurusan Berkas')

@section('content')
    <a href="{{ route('transactions.index') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-white border border-gray-300 rounded-lg text-gray-700 font-bold hover:bg-gray-50 transition-colors mb-6 shadow-sm">
        <i class="fa-solid fa-arrow-left"></i> Kembali ke Data Transaksi
    </a>

    <div class="max-w-4xl mx-auto bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        
        @if($errors->any())
            <div class="m-6 px-4 py-3 bg-red-50 border-l-4 border-red-500 text-red-700 rounded shadow-sm">
                <ul class="list-disc list-inside text-sm font-medium">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('transactions.store') }}" method="POST">
            @csrf
            
            <!-- HEADER FORM -->
            <div class="p-6 border-b border-gray-100 bg-gray-50/50 flex items-center gap-3">
                <div class="w-10 h-10 rounded-full bg-blue-100 flex items-center justify-center text-blue-600 text-lg">
                    <i class="fa-solid fa-file-signature"></i>
                </div>
                <div>
                    <h2 class="text-lg font-bold text-gray-800">Detail Layanan Pengurusan</h2>
                    <p class="text-xs text-gray-500">Masukkan detail kendaraan dan biaya jasa pengurusan.</p>
                </div>
            </div>

            <!-- ISI FORM -->
            <div class="p-8">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    
                    <!-- Pilih Kendaraan -->
                    <div class="col-span-1 md:col-span-2">
                        <label class="block text-sm font-bold text-gray-700 mb-1">Pilih Kendaraan Klien <span class="text-red-500">*</span></label>
                        <select name="vehicle_id" required class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-shadow bg-gray-50 focus:bg-white appearance-none text-gray-700">
                            <option value="">-- Cari dan Pilih Kendaraan --</option>
                            @foreach($vehicles ?? [] as $vehicle)
                                <!-- Jika user datang dari tombol "Urus Sekarang" di dashboard, otomatis pilih kendaraannya -->
                                <option value="{{ $vehicle->id }}" {{ (request('vehicle_id') == $vehicle->id || old('vehicle_id') == $vehicle->id) ? 'selected' : '' }}>
                                    {{ $vehicle->nopol }} - {{ $vehicle->merk }} {{ $vehicle->tipe }} (Milik: {{ $vehicle->client->nama_lengkap ?? 'Tanpa Nama' }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Jenis Layanan -->
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-1">Jenis Layanan <span class="text-red-500">*</span></label>
                        <select name="jenis_layanan" required class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-shadow bg-white appearance-none">
                            <option value="">-- Pilih Layanan --</option>
                            <option value="Pajak Tahunan" {{ old('jenis_layanan') == 'Pajak Tahunan' ? 'selected' : '' }}>Pajak Tahunan (Pengesahan)</option>
                            <option value="Pajak 5 Tahunan" {{ old('jenis_layanan') == 'Pajak 5 Tahunan' ? 'selected' : '' }}>Pajak 5 Tahunan (Ganti Kaleng)</option>
                            <option value="Balik Nama" {{ old('jenis_layanan') == 'Balik Nama' ? 'selected' : '' }}>Balik Nama (BBN-KB)</option>
                            <option value="Mutasi" {{ old('jenis_layanan') == 'Mutasi' ? 'selected' : '' }}>Mutasi Keluar / Masuk</option>
                            <option value="Urus STNK Hilang" {{ old('jenis_layanan') == 'Urus STNK Hilang' ? 'selected' : '' }}>Urus STNK / BPKB Hilang</option>
                        </select>
                    </div>

                    <!-- Status Proses -->
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-1">Status Pengerjaan Awal <span class="text-red-500">*</span></label>
                        <select name="status_proses" required class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-shadow bg-white appearance-none font-medium text-blue-700">
                            <option value="Pending" selected>Pending (Berkas Diterima)</option>
                            <option value="Cancel">Cancel</option>
                        </select>
                    </div>

                    <!-- Rincian Biaya (Pajak, Jasa, Lainnya) -->
                    <div class="col-span-1 md:col-span-2 bg-blue-50/30 p-6 rounded-xl border border-blue-100">
                        <h3 class="text-sm font-black text-gray-800 mb-4 border-b border-blue-200 pb-2">
                            <i class="fa-solid fa-file-invoice-dollar text-blue-500 mr-2"></i> Rincian Tagihan
                        </h3>
                        
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                            <!-- 1. Biaya Pajak -->
                            <div>
                                <label class="block text-xs font-bold text-gray-700 mb-1">Biaya Pajak <span class="text-red-500">*</span></label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                        <span class="text-gray-500 font-bold text-sm">Rp</span>
                                    </div>
                                    <input type="text" id="pajak_tampil" required placeholder="0" class="input-biaya w-full pl-10 pr-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none font-bold text-gray-800 transition-colors">
                                    <!-- Hapus variabel $transaction pada create.blade.php -->
                                    <input type="hidden" name="biaya_pajak" id="pajak_asli" value="{{ old('biaya_pajak' ?? 0) }}">
                                </div>
                            </div>

                            <!-- 2. Biaya Jasa -->
                            <div>
                                <label class="block text-xs font-bold text-gray-700 mb-1">Biaya Jasa <span class="text-red-500">*</span></label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                        <span class="text-gray-500 font-bold text-sm">Rp</span>
                                    </div>
                                    <input type="text" id="jasa_tampil" required placeholder="0" class="input-biaya w-full pl-10 pr-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none font-bold text-gray-800 transition-colors">
                                    <!-- Hapus variabel $transaction pada create.blade.php -->
                                    <input type="hidden" name="biaya_jasa" id="jasa_asli" value="{{ old('biaya_jasa' ?? 0) }}">
                                </div>
                            </div>

                            <!-- 3. Biaya Lainnya -->
                            <div class="col-span-1 md:col-span-3 mt-4 border-t border-blue-100 pt-4">
                                <label class="block text-sm font-bold text-gray-700 mb-3">Rincian Biaya Lainnya</label>
                                <div class="grid grid-cols-1 md:grid-cols-4 gap-4 bg-white p-4 rounded-lg border border-gray-200">
                                    
                                    <!-- Daftar 8 Input -->
                                    @php
                                        $rincianLain = [
                                            ['id' => 'loket_pendaftaran', 'label' => 'Loket Pendaftaran'],
                                            ['id' => 'loket_cek_fisik', 'label' => 'Loket Cek Fisik'],
                                            ['id' => 'acc_tidak_hadir', 'label' => 'Acc Tidak Hadir STNK'],
                                            ['id' => 'acc_domisili', 'label' => 'Acc Domisili (Beda Alamat)'],
                                            ['id' => 'loket_penetapan', 'label' => 'Loket Penetapan'],
                                            ['id' => 'loket_pengesahan_1', 'label' => 'Loket Pengesahan Pertama'],
                                            ['id' => 'loket_pengesahan_2', 'label' => 'Loket Pengesahan Kedua'],
                                            ['id' => 'bea_materai', 'label' => 'Bea Materai'],
                                        ];
                                    @endphp

                                    @foreach($rincianLain as $item)
                                    <div>
                                        <label class="block text-[11px] font-bold text-gray-600 mb-1">{{ $item['label'] }}</label>
                                        <div class="relative">
                                            <div class="absolute inset-y-0 left-0 flex items-center pl-2 pointer-events-none">
                                                <span class="text-gray-400 font-bold text-xs">Rp</span>
                                            </div>
                                            <!-- Hapus $transaction pada create.blade.php -->
                                            <input type="text" id="tampil_{{ $item['id'] }}" placeholder="0" class="input-detail-lain w-full pl-8 pr-2 py-1.5 border border-gray-300 rounded focus:ring-1 focus:ring-blue-500 outline-none font-bold text-gray-700 text-sm transition-colors">
                                            <input type="hidden" name="{{ $item['id'] }}" id="asli_{{ $item['id'] }}" value="{{ old($item['id'] ?? 0) }}">
                                        </div>
                                    </div>
                                    @endforeach

                                </div>
                                <div class="text-right mt-2">
                                    <span class="text-xs text-gray-500 font-bold">Subtotal Biaya Lain: </span>
                                    <span class="text-sm font-black text-blue-600" id="subtotal_lain">Rp 0</span>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Kalkulasi Total -->
                        <div class="mt-5 pt-4 border-t border-blue-200 flex items-center justify-between">
                            <span class="font-bold text-gray-700 uppercase tracking-wider text-sm">Total Keseluruhan</span>
                            <span class="text-2xl font-black text-blue-700" id="total_kalkulasi">Rp 0</span>
                        </div>
                    </div>

                    <!-- Tanggal Masuk -->
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-1">Tanggal Masuk Berkas *</label>
                        <input type="date" name="tgl_masuk" value="{{ old('tgl_masuk', date('Y-m-d')) }}" required class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-shadow bg-gray-50 focus:bg-white">
                    </div>

                </div>
            </div>

            <!-- TOMBOL ACTION -->
            <div class="p-8 pt-4 bg-gray-50 border-t border-gray-100 flex gap-3">
                <button type="submit" class="px-6 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-lg shadow-sm transition-colors flex items-center gap-2">
                    <i class="fa-solid fa-floppy-disk"></i> Simpan Transaksi
                </button>
                <a href="{{ route('transactions.index') }}" class="px-6 py-2.5 bg-white border border-gray-300 hover:bg-gray-50 text-gray-700 font-bold rounded-lg transition-colors">
                    Batal
                </a>
            </div>
        </form>
    </div>

    <!-- SCRIPT UNTUK FORMAT RUPIAH OTOMATIS -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const formatRupiah = (angka) => new Intl.NumberFormat('id-ID').format(angka || 0);

            // Input Utama
            const pajakTampil = document.getElementById('pajak_tampil');
            const pajakAsli = document.getElementById('pajak_asli');
            const jasaTampil = document.getElementById('jasa_tampil');
            const jasaAsli = document.getElementById('jasa_asli');
            
            // Input Detail Biaya Lain
            const detailLainInputs = document.querySelectorAll('.input-detail-lain');
            
            // Label Total
            const labelSubtotalLain = document.getElementById('subtotal_lain');
            const labelTotal = document.getElementById('total_kalkulasi');

            const hitungTotal = () => {
                let totalPajak = parseInt(pajakAsli.value) || 0;
                let totalJasa = parseInt(jasaAsli.value) || 0;
                let subtotalLain = 0;

                // Hitung 8 rincian
                detailLainInputs.forEach(inputTampil => {
                    let idAsli = inputTampil.id.replace('tampil_', 'asli_');
                    let inputAsli = document.getElementById(idAsli);
                    let nilai = parseInt(inputAsli.value) || 0;
                    subtotalLain += nilai;
                    
                    if(inputAsli.value > 0 && document.activeElement !== inputTampil) {
                        inputTampil.value = formatRupiah(nilai);
                    }
                });

                // Set tampilan
                if(pajakAsli.value > 0 && document.activeElement !== pajakTampil) pajakTampil.value = formatRupiah(totalPajak);
                if(jasaAsli.value > 0 && document.activeElement !== jasaTampil) jasaTampil.value = formatRupiah(totalJasa);
                
                labelSubtotalLain.innerText = 'Rp ' + formatRupiah(subtotalLain);
                labelTotal.innerText = 'Rp ' + formatRupiah(totalPajak + totalJasa + subtotalLain);
            };

            // Event Listener untuk semua input text yang format uang
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
    </script>

@endsection
