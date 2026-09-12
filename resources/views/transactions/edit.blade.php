@extends('layouts.app')

@section('title', 'Edit Transaksi')
@section('header_title', 'Update Status Pengurusan')

@section('content')
    <a href="{{ route('transactions.index') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-white border border-gray-300 rounded-lg text-gray-700 font-bold hover:bg-gray-50 transition-colors mb-6 shadow-sm">
        <i class="fa-solid fa-arrow-left"></i> Kembali ke Data Transaksi
    </a>

    <div class="max-w-4xl mx-auto bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        
        @if($errors->any())
        <div class="px-4 py-3 bg-red-50 border-l-4 border-red-500 text-red-700 rounded shadow-sm">
            <ul class="list-disc list-inside text-sm font-medium">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <form action="{{ route('transactions.update', $transactions->first()->id) }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                <!-- HEADER GLOBAL -->
                <div class="p-6 border-b border-gray-100 bg-gray-50/50 flex flex-col md:flex-row justify-between gap-4 md:items-center">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-full bg-yellow-100 flex items-center justify-center text-yellow-600 text-lg">
                            <i class="fa-solid fa-pen-to-square"></i>
                        </div>
                        <div>
                            <h2 class="text-lg font-bold text-gray-800">Edit Invoice: {{ $baseInvoice }}</h2>
                            <p class="text-xs text-gray-500">Klien: {{ $client->nama_lengkap ?? 'Tanpa Nama' }} ({{ $client->no_whatsapp ?? '-' }})</p>
                        </div>
                    </div>
                    
                    <!-- Tanggal Masuk & Selesai (Global) -->
                    <div class="flex gap-3 w-full md:w-auto">
                        <div>
                            <label class="block text-[11px] font-bold text-gray-700 mb-1">Tgl Masuk Berkas *</label>
                            <input type="date" name="tgl_masuk" value="{{ \Carbon\Carbon::parse($transactions->first()->tgl_masuk)->format('Y-m-d') }}" required class="w-full px-3 py-1.5 border border-gray-300 rounded focus:ring-1 outline-none text-sm">
                        </div>
                        <div>
                            <label class="block text-[11px] font-bold text-gray-700 mb-1">Tgl Selesai (Opsional)</label>
                            <input type="date" name="tgl_selesai" value="{{ $transactions->first()->tgl_selesai ? \Carbon\Carbon::parse($transactions->first()->tgl_selesai)->format('Y-m-d') : '' }}" class="w-full px-3 py-1.5 border border-gray-300 rounded focus:ring-1 outline-none text-sm">
                        </div>
                    </div>
                </div>

                <!-- LOOPING TRANSAKSI DALAM INVOICE INI -->
                <div class="p-6 space-y-8">
                    @foreach($transactions as $trx)
                    <div class="trx-card relative border border-gray-200 rounded-xl p-5 bg-white shadow-sm" data-tid="{{ $trx->id }}">
                        
                        <div class="absolute -top-3 left-4 bg-white px-2 text-xs font-black text-gray-400">
                            INVOICE: {{ $trx->invoice_no }}
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-3">
                            <!-- Info Kendaraan & Status -->
                            <div>
                                <div class="mb-4">
                                    <label class="block text-sm font-bold text-gray-700 mb-1">Kendaraan</label>
                                    <input type="text" disabled value="{{ $trx->vehicle->nopol }} - {{ $trx->vehicle->merk }} {{ $trx->vehicle->tipe }}" class="w-full px-4 py-2 border border-gray-200 rounded-lg bg-gray-50 text-gray-600 font-bold">
                                </div>
                                
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-xs font-bold text-gray-700 mb-1">Jenis Layanan <span class="text-red-500">*</span></label>
                                        <select name="transaksi[{{ $trx->id }}][jenis_layanan]" class="w-full px-3 py-2 border border-gray-300 rounded-lg outline-none text-sm">
                                            <option value="Pajak Tahunan" {{ $trx->jenis_layanan == 'Pajak Tahunan' ? 'selected' : '' }}>Pajak Tahunan</option>
                                            <option value="Pajak 5 Tahunan" {{ $trx->jenis_layanan == 'Pajak 5 Tahunan' ? 'selected' : '' }}>Pajak 5 Tahunan</option>
                                            <option value="Balik Nama" {{ $trx->jenis_layanan == 'Balik Nama' ? 'selected' : '' }}>Balik Nama</option>
                                            <option value="Mutasi Keluar" {{ $trx->jenis_layanan == 'Mutasi Keluar' ? 'selected' : '' }}>Mutasi Keluar</option>
                                        </select>
                                    </div>
                                    <div>
                                        <label class="block text-xs font-bold text-gray-700 mb-1">Status Pengerjaan <span class="text-red-500">*</span></label>
                                        <select name="transaksi[{{ $trx->id }}][status_proses]" class="w-full px-3 py-2 border border-gray-300 rounded-lg outline-none text-sm font-bold {{ $trx->status_proses == 'Done' ? 'text-green-700' : 'text-blue-700' }}">
                                            <option value="Pending" {{ $trx->status_proses == 'Pending' ? 'selected' : '' }}>Pending</option>
                                            <option value="Done" {{ $trx->status_proses == 'Done' ? 'selected' : '' }}>Done (Selesai)</option>
                                            <option value="Cancel" {{ $trx->status_proses == 'Cancel' ? 'selected' : '' }}>Cancel</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <!-- Rincian Biaya (Pajak, Jasa, Lainnya) -->
                            <div class="bg-blue-50/50 p-4 rounded-xl border border-blue-100">
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4 border-b border-blue-200 pb-4">
                                    <div>
                                        <label class="block text-xs font-bold text-gray-700 mb-1">Biaya Pajak</label>
                                        <div class="relative">
                                            <span class="absolute inset-y-0 left-3 flex items-center text-gray-500 font-bold text-sm">Rp</span>
                                            <input type="text" id="pajak_tampil_{{ $trx->id }}" value="{{ $trx->biaya_pajak }}" class="w-full pl-10 pr-3 py-2 border border-gray-300 rounded-lg focus:ring-1 font-bold text-gray-800 text-sm">
                                            <input type="hidden" name="transaksi[{{ $trx->id }}][biaya_pajak]" id="pajak_asli_{{ $trx->id }}" value="{{ $trx->biaya_pajak }}">
                                        </div>
                                    </div>
                                    <div>
                                        <label class="block text-xs font-bold text-gray-700 mb-1">Biaya Jasa</label>
                                        <div class="relative">
                                            <span class="absolute inset-y-0 left-3 flex items-center text-gray-500 font-bold text-sm">Rp</span>
                                            <input type="text" id="jasa_tampil_{{ $trx->id }}" value="{{ $trx->biaya_jasa }}" class="w-full pl-10 pr-3 py-2 border border-gray-300 rounded-lg focus:ring-1 font-bold text-gray-800 text-sm">
                                            <input type="hidden" name="transaksi[{{ $trx->id }}][biaya_jasa]" id="jasa_asli_{{ $trx->id }}" value="{{ $trx->biaya_jasa }}">
                                        </div>
                                    </div>
                                </div>

                                <label class="block text-xs font-bold text-gray-700 mb-2">Rincian Biaya Lainnya</label>
                                <div class="grid grid-cols-2 gap-3 bg-white p-3 rounded-lg border border-gray-200">
                                    @php
                                        $rincianLain = [
                                            ['id' => 'loket_pendaftaran', 'label' => 'Loket Pendaftaran', 'val' => $trx->loket_pendaftaran],
                                            ['id' => 'loket_cek_fisik', 'label' => 'Cek Fisik', 'val' => $trx->loket_cek_fisik],
                                            ['id' => 'acc_tidak_hadir', 'label' => 'Acc Tdk Hadir', 'val' => $trx->acc_tidak_hadir],
                                            ['id' => 'acc_domisili', 'label' => 'Acc Domisili', 'val' => $trx->acc_domisili],
                                            ['id' => 'loket_penetapan', 'label' => 'Penetapan', 'val' => $trx->loket_penetapan],
                                            ['id' => 'loket_pengesahan_1', 'label' => 'Pengesahan 1', 'val' => $trx->loket_pengesahan_1],
                                            ['id' => 'loket_pengesahan_2', 'label' => 'Pengesahan 2', 'val' => $trx->loket_pengesahan_2],
                                            ['id' => 'bea_materai', 'label' => 'Bea Materai', 'val' => $trx->bea_materai],
                                        ];
                                    @endphp
                                    @foreach($rincianLain as $item)
                                    <div>
                                        <label class="block text-[10px] font-bold text-gray-600 mb-1">{{ $item['label'] }}</label>
                                        <div class="relative">
                                            <span class="absolute inset-y-0 left-2 flex items-center text-gray-400 font-bold text-[10px]">Rp</span>
                                            <input type="text" id="tampil_{{ $item['id'] }}_{{ $trx->id }}" value="{{ $item['val'] }}" class="input-detail-lain w-full pl-7 pr-2 py-1 border border-gray-300 rounded font-bold text-gray-700 text-xs">
                                            <input type="hidden" name="transaksi[{{ $trx->id }}][{{ $item['id'] }}]" id="asli_{{ $item['id'] }}_{{ $trx->id }}" value="{{ $item['val'] }}">
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                                
                                <div class="mt-3 pt-3 border-t border-blue-200 flex justify-between items-center">
                                    <span class="text-xs font-bold text-gray-600 uppercase">Subtotal</span>
                                    <span class="text-lg font-black text-blue-700" id="total_kalkulasi_{{ $trx->id }}">Rp 0</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>

                <!-- TOMBOL ACTION GLOBAL -->
                <div class="p-6 bg-gray-50 border-t border-gray-100 flex gap-3">
                    <button type="submit" class="px-6 py-2.5 bg-yellow-500 hover:bg-yellow-600 text-white font-bold rounded-lg shadow-sm flex items-center gap-2">
                        <i class="fa-solid fa-floppy-disk"></i> Simpan Perubahan
                    </button>
                    <a href="{{ route('transactions.index') }}" class="px-6 py-2.5 bg-white border border-gray-300 hover:bg-gray-50 text-gray-700 font-bold rounded-lg">
                        Batal
                    </a>
                </div>
            </div>
        </form>
    </div>

    <!-- SCRIPT UNTUK FORMAT RUPIAH OTOMATIS -->
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
    });
    </script>

@endsection
