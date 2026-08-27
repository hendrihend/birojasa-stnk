@extends('layouts.app')

@section('title', 'Edit Transaksi')
@section('header_title', 'Update Status Pengurusan')

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

        <form action="{{ route('transactions.update', $transaction->id) }}" method="POST">
            @csrf
            @method('PUT')
            
            <!-- HEADER FORM -->
            <div class="p-6 border-b border-gray-100 bg-gray-50/50 flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-full bg-blue-100 flex items-center justify-center text-blue-600 text-lg">
                        <i class="fa-solid fa-pen-to-square"></i>
                    </div>
                    <div>
                        <h2 class="text-lg font-bold text-gray-800">Update Data Transaksi</h2>
                        <p class="text-xs text-gray-500">Ubah status pengerjaan atau sesuaikan total biaya.</p>
                    </div>
                </div>
                
                <!-- Badge Tanggal Transaksi -->
                <div class="text-right">
                    <span class="text-xs text-gray-500 block mb-1">Dibuat pada:</span>
                    <span class="bg-gray-200 text-gray-700 text-xs font-bold px-3 py-1 rounded-full">
                        {{ ($transaction->created_at->setTimezone('Asia/Jakarta')->format('d M Y, H:i:s')) }}
                    </span>
                </div>
            </div>

            <!-- ISI FORM -->
            <div class="p-8">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    
                    <!-- Info Kendaraan (Dibuat Read-only agar tidak tidak sengaja terubah) -->
                    <div class="col-span-1 md:col-span-2">
                        <label class="block text-sm font-bold text-gray-700 mb-1">Kendaraan Klien</label>
                        <div class="w-full px-4 py-3 border border-gray-200 rounded-lg bg-gray-100 text-gray-600 flex items-center gap-3">
                            <i class="fa-solid fa-car text-gray-400"></i>
                            <span class="font-bold tracking-wider">{{ $transaction->vehicle->nopol }}</span>
                            <span>&mdash; {{ $transaction->vehicle->merk }} {{ $transaction->vehicle->tipe }}</span>
                            <span class="ml-auto text-xs bg-gray-200 px-2 py-1 rounded font-bold text-gray-500">Milik: {{ $transaction->vehicle->client->nama_lengkap ?? 'Tanpa Nama' }}</span>
                        </div>
                        <!-- Input hidden untuk mengirim ID Kendaraan agar tidak error saat validasi -->
                        <input type="hidden" name="vehicle_id" value="{{ $transaction->vehicle_id }}">
                    </div>

                    <!-- Jenis Layanan -->
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-1">Jenis Layanan <span class="text-red-500">*</span></label>
                        <select name="jenis_layanan" required class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-shadow bg-white appearance-none">
                            <option value="Pajak Tahunan" {{ (old('jenis_layanan', $transaction->jenis_layanan) == 'Pajak Tahunan') ? 'selected' : '' }}>Pajak Tahunan (Pengesahan)</option>
                            <option value="Pajak 5 Tahunan" {{ (old('jenis_layanan', $transaction->jenis_layanan) == 'Pajak 5 Tahunan') ? 'selected' : '' }}>Pajak 5 Tahunan (Ganti Kaleng)</option>
                            <option value="Balik Nama" {{ (old('jenis_layanan', $transaction->jenis_layanan) == 'Balik Nama') ? 'selected' : '' }}>Balik Nama (BBN-KB)</option>
                            <option value="Mutasi" {{ (old('jenis_layanan', $transaction->jenis_layanan) == 'Mutasi') ? 'selected' : '' }}>Mutasi Keluar / Masuk</option>
                            <option value="Urus STNK Hilang" {{ (old('jenis_layanan', $transaction->jenis_layanan) == 'Urus STNK Hilang') ? 'selected' : '' }}>Urus STNK / BPKB Hilang</option>
                        </select>
                    </div>

                    <!-- Status Proses (Bagian Paling Penting) -->
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-1">Update Status <span class="text-red-500">*</span></label>
                        <select name="status_proses" required class="w-full px-4 py-2.5 border-2 border-blue-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-shadow bg-blue-50 text-blue-800 font-bold appearance-none">
                            <option value="Pending" {{ (old('status_proses', $transaction->status_proses) == 'Pending') ? 'selected' : '' }}>Pending (Berkas Diterima)</option>
                            <option value="Cancel" {{ (old('status_proses', $transaction->status_proses) == 'Cancel') ? 'selected' : '' }}>Cancel</option>
                            <!-- Opsi Selesai dibuat menarik -->
                            <option value="Done" {{ (old('status_proses', $transaction->status_proses) == 'Done') ? 'selected' : '' }}>DONE</option>
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
                                <label class="block text-xs font-bold text-gray-700 mb-1">Pajak & PNBP <span class="text-red-500">*</span></label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                        <span class="text-gray-500 font-bold text-sm">Rp</span>
                                    </div>
                                    <input type="text" id="pajak_tampil" required placeholder="0" class="input-biaya w-full pl-10 pr-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none font-bold text-gray-800 transition-colors">
                                    <!-- Hapus variabel $transaction pada create.blade.php -->
                                    <input type="hidden" name="biaya_pajak" id="pajak_asli" value="{{ old('biaya_pajak', $transaction->biaya_pajak ?? 0) }}">
                                </div>
                            </div>

                            <!-- 2. Biaya Jasa -->
                            <div>
                                <label class="block text-xs font-bold text-gray-700 mb-1">Jasa Biro Jasa <span class="text-red-500">*</span></label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                        <span class="text-gray-500 font-bold text-sm">Rp</span>
                                    </div>
                                    <input type="text" id="jasa_tampil" placeholder="0" class="input-biaya w-full pl-10 pr-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none font-bold text-gray-800 transition-colors">
                                    <!-- Hapus variabel $transaction pada create.blade.php -->
                                    <input type="hidden" name="biaya_jasa" id="jasa_asli" value="{{ old('biaya_jasa', $transaction->biaya_jasa ?? 0) }}">
                                </div>
                            </div>

                            <!-- 3. Biaya Lainnya -->
                            <div>
                                <label class="block text-xs font-bold text-gray-700 mb-1">Biaya Lain / Materai <span class="text-red-500">*</span></label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                        <span class="text-gray-500 font-bold text-sm">Rp</span>
                                    </div>
                                    <input type="text" id="lain_tampil" placeholder="0" class="input-biaya w-full pl-10 pr-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none font-bold text-gray-800 transition-colors">
                                    <!-- Hapus variabel $transaction pada create.blade.php -->
                                    <input type="hidden" name="biaya_lain" id="lain_asli" value="{{ old('biaya_lain', $transaction->biaya_lain ?? 0) }}">
                                </div>
                            </div>
                        </div>

                        <!-- Kalkulasi Total -->
                        <div class="mt-5 pt-4 border-t border-blue-200 flex items-center justify-between">
                            <span class="font-bold text-gray-700 uppercase tracking-wider text-sm">Total Keseluruhan</span>
                            <span class="text-2xl font-black text-blue-700" id="total_kalkulasi">Rp 0</span>
                        </div>
                    </div>

                    <!-- Tanggal Selesai -->
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-1">Tanggal Selesai (Opsional)</label>
                        <input type="date" name="tgl_selesai" value="{{ old('tgl_selesai', $transaction->tgl_selesai) }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-shadow bg-gray-50 focus:bg-white appearance-none">
                        <small class="text-xs text-gray-500 mt-1">Jika status diubah ke 'Selesai' dan ini dikosongkan, tanggal otomatis diisi hari ini.</small>
                    </div>


                </div>
                
            </div>

            <!-- TOMBOL ACTION -->
            <div class="p-8 pt-4 bg-gray-50 border-t border-gray-100 flex gap-3">
                <button type="submit" class="px-6 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-lg shadow-sm transition-colors flex items-center gap-2">
                    <i class="fa-solid fa-floppy-disk"></i> Simpan Perubahan
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
            // Fungsi format Rupiah (menggunakan titik)
            const formatRupiah = (angka) => new Intl.NumberFormat('id-ID').format(angka || 0);

            // Definisi elemen input
            const inputs = [
                { tampil: document.getElementById('pajak_tampil'), asli: document.getElementById('pajak_asli') },
                { tampil: document.getElementById('jasa_tampil'), asli: document.getElementById('jasa_asli') },
                { tampil: document.getElementById('lain_tampil'), asli: document.getElementById('lain_asli') }
            ];
            const labelTotal = document.getElementById('total_kalkulasi');

            // Fungsi Kalkulasi & Tampilan
            const hitungTotal = () => {
                let total = 0;
                inputs.forEach(item => {
                    let nilai = parseInt(item.asli.value) || 0;
                    total += nilai;
                    // Update tampilan format rupiah saat pertama kali diload (mode edit)
                    if(item.asli.value > 0) item.tampil.value = formatRupiah(nilai); 
                });
                labelTotal.innerText = 'Rp ' + formatRupiah(total);
            };

            // Pasang Event Listener saat user mengetik
            inputs.forEach(item => {
                item.tampil.addEventListener('input', function(e) {
                    let angkaMurni = this.value.replace(/[^0-9]/g, ''); // Hapus selain angka
                    item.asli.value = angkaMurni; // Simpan ke input hidden
                    this.value = angkaMurni ? formatRupiah(angkaMurni) : ''; // Tampilkan dengan titik
                    hitungTotal(); // Panggil fungsi hitung ulang
                });
            });

            // Jalankan sekali saat halaman dimuat (untuk memunculkan nilai dari database jika ada)
            hitungTotal();
        });
    </script>

@endsection
