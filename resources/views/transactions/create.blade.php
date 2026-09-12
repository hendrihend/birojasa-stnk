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
                
                <!-- Tanggal Masuk (Global untuk 1 Invoice) -->
                <div class="w-full md:w-auto">
                    <label class="block text-xs font-bold text-gray-700 mb-1">Tanggal Masuk Berkas *</label>
                    <input type="date" name="tgl_masuk" value="{{ old('tgl_masuk', date('Y-m-d')) }}" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none text-sm">
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
                                    <label class="block text-xs font-bold text-gray-700 mb-1">Jenis Layanan <span class="text-red-500">*</span></label>
                                    <select name="transaksi[{{ $vehicle->id }}][jenis_layanan]" class="w-full px-3 py-2 border border-gray-300 rounded-lg outline-none text-sm">
                                        <option value="Pajak Tahunan">Pajak Tahunan</option>
                                        <option value="Pajak 5 Tahunan">Pajak 5 Tahunan</option>
                                        <option value="Balik Nama">Balik Nama</option>
                                        <option value="Mutasi Keluar">Mutasi Keluar</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-gray-700 mb-1">Status Awal <span class="text-red-500">*</span></label>
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
                                    <label class="block text-xs font-bold text-gray-700 mb-1">Biaya Pajak</label>
                                    <div class="relative">
                                        <span class="absolute inset-y-0 left-3 flex items-center text-gray-500 font-bold text-sm">Rp</span>
                                        <input type="text" id="pajak_tampil_{{ $vehicle->id }}" placeholder="0" class="w-full pl-10 pr-3 py-2 border border-gray-300 rounded-lg focus:ring-1 focus:ring-blue-500 font-bold text-gray-800 text-sm">
                                        <input type="hidden" name="transaksi[{{ $vehicle->id }}][biaya_pajak]" id="pajak_asli_{{ $vehicle->id }}">
                                    </div>
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-gray-700 mb-1">Biaya Jasa</label>
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
                                        ['id' => 'acc_tidak_hadir', 'label' => 'Acc Tdk Hadir'],
                                        ['id' => 'acc_domisili', 'label' => 'Acc Domisili'],
                                        ['id' => 'loket_penetapan', 'label' => 'Penetapan'],
                                        ['id' => 'loket_pengesahan_1', 'label' => 'Pengesahan 1'],
                                        ['id' => 'loket_pengesahan_2', 'label' => 'Pengesahan 2'],
                                        ['id' => 'bea_materai', 'label' => 'Bea Materai'],
                                    ];
                                @endphp
                                @foreach($rincianLain as $item)
                                <div>
                                    <label class="block text-[10px] font-bold text-gray-600 mb-1">{{ $item['label'] }}</label>
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
    <!-- MODAL PREVIEW INVOICE KOLEKTIF                       -->
    <!-- ==================================================== -->
    <div id="previewModal" class="hidden fixed inset-0 z-50 bg-gray-900/60 backdrop-blur-sm overflow-y-auto flex items-center justify-center p-4 transition-opacity">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-5xl overflow-hidden flex flex-col max-h-[90vh]">
            
            <!-- Modal Header -->
            <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center bg-gray-50/50">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center text-lg">
                        <i class="fa-solid fa-file-invoice-dollar"></i>
                    </div>
                    <div>
                        <h3 class="font-black text-gray-800 text-lg">Preview Transaksi Kolektif</h3>
                        <p class="text-xs text-gray-500" id="previewClientName">Klien: {{ $selectedClient->nama_lengkap ?? '' }}</p>
                    </div>
                </div>
                <button type="button" id="btnCloseModal" class="text-gray-400 hover:text-red-500 transition-colors">
                    <i class="fa-solid fa-xmark text-2xl"></i>
                </button>
            </div>

            <!-- Modal Body (Isi Tabel) -->
            <div class="p-6 overflow-y-auto bg-white flex-1">
                <table class="w-full text-sm text-left border border-gray-200 rounded-lg overflow-hidden">
                    <thead class="bg-blue-50 text-blue-800 border-b border-blue-100">
                        <tr>
                            <th class="p-3">Kendaraan</th>
                            <th class="p-3">Layanan</th>
                            <th class="p-3 text-right">Pajak</th>
                            <th class="p-3 text-right">Jasa</th>
                            <th class="p-3 text-right">Biaya Lain</th>
                            <th class="p-3 text-right">Subtotal</th>
                        </tr>
                    </thead>
                    <tbody id="previewTableBody" class="divide-y divide-gray-100">
                        <!-- Baris akan diisi otomatis oleh JavaScript -->
                    </tbody>
                    <tfoot class="bg-gray-50 border-t border-gray-200">
                        <tr>
                            <th colspan="5" class="p-4 text-right font-bold text-gray-700 uppercase tracking-wider text-xs">Grand Total Keseluruhan:</th>
                            <th class="p-4 text-right text-xl font-black text-blue-700" id="previewGrandTotal">Rp 0</th>
                        </tr>
                    </tfoot>
                </table>
                
                <div class="mt-4 p-4 bg-yellow-50 border border-yellow-200 rounded-lg text-yellow-800 text-sm flex gap-2">
                    <i class="fa-solid fa-circle-info mt-0.5"></i>
                    <p><strong>Pastikan angka sudah benar.</strong> Saat Anda menekan Konfirmasi, sistem akan membuatkan beberapa nomor urut untuk Invoice Kolektif ini.</p>
                </div>
            </div>

            <!-- Modal Footer (Tombol Aksi) -->
            <div class="px-6 py-4 border-t border-gray-100 bg-gray-50 flex justify-end gap-3">
                <button type="button" id="btnCancelModal" class="px-6 py-2.5 bg-white border border-gray-300 hover:bg-gray-50 text-gray-700 font-bold rounded-lg transition-colors">
                    Cek Lagi
                </button>
                <!-- Tombol inilah yang akan memicu Submit Form sesungguhnya -->
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

        // ==============================================================
        // LOGIKA POP-UP PREVIEW INVOICE (BARU)
        // ==============================================================
        const btnShowPreview = document.getElementById('btnShowPreview');
        const previewModal = document.getElementById('previewModal');
        const previewTableBody = document.getElementById('previewTableBody');
        const previewGrandTotal = document.getElementById('previewGrandTotal');
        
        if(btnShowPreview) {
            btnShowPreview.addEventListener('click', function() {
                let htmlContent = '';
                let grandTotalSemua = 0;
                let selectedCount = 0;

                // Looping ke semua kotak kendaraan
                document.querySelectorAll('.vehicle-card').forEach(card => {
                    const vid = card.dataset.vid;
                    const checkbox = card.querySelector(`input[name="transaksi[${vid}][is_selected]"]`);
                    
                    // HANYA ambil data yang dicentang
                    if (checkbox && checkbox.checked) {
                        selectedCount++;
                        
                        // 1. Ambil Nama Kendaraan
                        const nopolText = card.querySelector('input[disabled]').value;
                        
                        // 2. Ambil Layanan
                        const layanan = card.querySelector(`select[name="transaksi[${vid}][jenis_layanan]"]`).options[card.querySelector(`select[name="transaksi[${vid}][jenis_layanan]"]`).selectedIndex].text;
                        
                        // 3. Ambil Biaya Asli (dari hidden input)
                        const pajak = parseInt(document.getElementById(`pajak_asli_${vid}`).value) || 0;
                        const jasa = parseInt(document.getElementById(`jasa_asli_${vid}`).value) || 0;
                        
                        // 4. Hitung Ulang Biaya Lain per card
                        let subtotalLain = 0;
                        card.querySelectorAll('.input-detail-lain').forEach(inputTampil => {
                            let idAsli = inputTampil.id.replace('tampil_', 'asli_');
                            subtotalLain += parseInt(document.getElementById(idAsli).value) || 0;
                        });

                        const subtotalPerMobil = pajak + jasa + subtotalLain;
                        grandTotalSemua += subtotalPerMobil;

                        // 5. Susun Baris Tabel HTML
                        htmlContent += `
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="p-3 font-bold text-gray-800">${nopolText}</td>
                                <td class="p-3 text-gray-600">${layanan}</td>
                                <td class="p-3 text-right text-gray-600">Rp ${formatRupiah(pajak)}</td>
                                <td class="p-3 text-right text-gray-600">Rp ${formatRupiah(jasa)}</td>
                                <td class="p-3 text-right text-gray-600">Rp ${formatRupiah(subtotalLain)}</td>
                                <td class="p-3 text-right font-bold text-blue-600">Rp ${formatRupiah(subtotalPerMobil)}</td>
                            </tr>
                        `;
                    }
                });

                // Validasi: Cegah modal muncul kalau tidak ada yang dicentang
                if (selectedCount === 0) {
                    alert('Silakan centang minimal 1 kendaraan yang ingin diproses!');
                    return;
                }

                // Tempelkan data ke dalam Modal
                previewTableBody.innerHTML = htmlContent;
                previewGrandTotal.innerText = 'Rp ' + formatRupiah(grandTotalSemua);

                // Tampilkan Modal
                previewModal.classList.remove('hidden');
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
