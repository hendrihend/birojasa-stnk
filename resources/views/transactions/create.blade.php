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

                    <!-- Total Biaya -->
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-1">Total Biaya (Pajak + Jasa) <span class="text-red-500">*</span></label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 flex items-center pl-4 pointer-events-none">
                                <span class="text-gray-500 font-bold">Rp</span>
                            </div>
                            <!-- 1. Input Tampil (Yang dilihat user, bisa format titik) -->
                            <input type="text" id="biaya_tampil" required placeholder="Contoh: 1.500.000"
                                class="w-full pl-12 pr-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-shadow bg-white text-lg font-bold text-gray-800">
                            <!-- 2. Input Asli (Tersembunyi, angka murni untuk dikirim ke database) -->
                            <!-- Catatan: Untuk di create.blade.php hapus $transaction->total_biaya -->
                            <input type="hidden" name="total_biaya" id="biaya_asli" value="{{ old('total_biaya', $transaction->total_biaya ?? '') }}">
                        </div>
                        <p class="text-[11px] text-gray-500 mt-1">Angka akan otomatis diberi pemisah ribuan (titik).</p>
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
            const inputTampil = document.getElementById('biaya_tampil');
            const inputAsli = document.getElementById('biaya_asli');

            // Fungsi format titik menggunakan bawaan Javascript (Intl)
            const formatRupiah = (angka) => {
                return new Intl.NumberFormat('id-ID').format(angka);
            }

            // 1. Jalankan saat halaman pertama kali dimuat (Berguna untuk mode Edit / Old input)
            if (inputAsli.value) {
                inputTampil.value = formatRupiah(inputAsli.value);
            }

            // 2. Jalankan setiap kali user mengetik
            inputTampil.addEventListener('input', function(e) {
                // Hapus semua karakter selain angka 0-9
                let angkaMurni = this.value.replace(/[^0-9]/g, '');
                
                // Simpan angka murni ke input yang tersembunyi untuk dikirim ke database
                inputAsli.value = angkaMurni;
                
                // Tampilkan kembali angka yang sudah diformat dengan titik ke layar
                if(angkaMurni) {
                    this.value = formatRupiah(angkaMurni);
                } else {
                    this.value = '';
                }
            });
        });
    </script>

@endsection
