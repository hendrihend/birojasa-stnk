@extends('layouts.app')

@section('title', 'Detail Kendaraan')
@section('header_title', 'Informasi Kendaraan: ' . $vehicle->nopol)

@section('content')
    <a href="{{ route('vehicles.index') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-white border border-gray-300 rounded-lg text-gray-700 font-semibold hover:bg-gray-50 transition-colors mb-6 shadow-sm">
        <i class="fa-solid fa-arrow-left text-sm"></i> Kembali
    </a>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Info Kendaraan -->
        <div class="lg:col-span-2 bg-white rounded-xl shadow-sm border border-gray-300 overflow-hidden">
            <div class="p-6 border-b border-gray-200 bg-gray-50">
                <h3 class="text-lg font-semibold text-gray-800">Detail Informasi Kendaraan</h3>
            </div>
            <div class="p-6">
                <dl class="divide-y divide-gray-200">
                    <div class="py-4 grid grid-cols-1 sm:grid-cols-3 sm:gap-4">
                        <dt class="text-sm font-semibold text-gray-500">Nomor Polisi</dt>
                        <dd class="mt-1 text-xl font-semibold text-gray-900 sm:col-span-2 sm:mt-0 tracking-wider uppercase">
                            {{ $vehicle->nopol }}
                        </dd>
                    </div>
                    <div class="py-4 grid grid-cols-1 sm:grid-cols-3 sm:gap-4">
                        <dt class="text-sm font-semibold text-gray-500">Merk & Tipe</dt>
                        <dd class="mt-1 text-base text-gray-900 sm:col-span-2 sm:mt-0">
                            {{ $vehicle->merk }} <span class="text-gray-400">&middot;</span> {{ $vehicle->tipe ?? '-' }}
                        </dd>
                    </div>
                    <div class="py-4 grid grid-cols-1 sm:grid-cols-3 sm:gap-4">
                        <dt class="text-sm font-semibold text-gray-500">Nomor Rangka</dt>
                        <dd class="mt-1 text-base text-gray-900 sm:col-span-2 sm:mt-0 font-medium">
                            {{ $vehicle->no_rangka ?? '-' }}
                        </dd>
                    </div>
                    <div class="py-4 grid grid-cols-1 sm:grid-cols-3 sm:gap-4">
                        <dt class="text-sm font-semibold text-gray-500">Nomor Mesin</dt>
                        <dd class="mt-1 text-base text-gray-900 sm:col-span-2 sm:mt-0 font-medium">
                            {{ $vehicle->no_mesin ?? '-' }}
                        </dd>
                    </div>
                    <div class="py-4 grid grid-cols-1 sm:grid-cols-3 sm:gap-4">
                        <dt class="text-sm font-semibold text-gray-500">Tahun Pembuatan</dt>
                        <dd class="mt-1 text-base text-gray-900 sm:col-span-2 sm:mt-0 font-medium">
                            {{ $vehicle->tahun_pembuatan ?? '-' }}
                        </dd>
                    </div>
                    <div class="py-4 grid grid-cols-1 sm:grid-cols-3 sm:gap-4">
                        <dt class="text-sm font-semibold text-gray-500">Warna</dt>
                        <dd class="mt-1 text-base text-gray-900 sm:col-span-2 sm:mt-0 font-medium">
                            {{ $vehicle->warna ?? '-' }}
                        </dd>
                    </div>
                    <div class="py-4 grid grid-cols-1 sm:grid-cols-3 sm:gap-4">
                        <dt class="text-sm font-semibold text-gray-500">Nama di STNK</dt>
                        <dd class="mt-1 text-base text-gray-900 sm:col-span-2 sm:mt-0 font-medium">
                            {{ $vehicle->nama_pemilik }}
                        </dd>
                    </div>
                    <div class="py-4 grid grid-cols-1 sm:grid-cols-3 sm:gap-4">
                        <dt class="text-sm font-semibold text-gray-500">Nama Klien</dt>
                        <dd class="mt-1 text-base text-gray-800 font-medium sm:col-span-2 sm:mt-0">
                            {{ $vehicle->client->nama_lengkap ?? 'Tanpa Pemilik' }}
                        </dd>
                    </div>
                </dl>
                
                <!-- Tombol ke Arsip Dokumen -->
                <div class="mt-6 pt-6 border-t border-gray-200">
                    <a href="{{ route('documents.index', $vehicle->id) }}" class="inline-flex items-center gap-2 px-5 py-2.5 bg-green-600 hover:bg-green-700 text-white font-semibold rounded-lg shadow-sm transition-colors">
                        <i class="fa-solid fa-folder-open"></i> Lihat Arsip Dokumen Kendaraan
                    </a>
                </div>
                
            </div>
        </div>

        <!-- Panel QR Code (Kanan, Memakan 1 Kolom) -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-300 p-8 flex flex-col items-center justify-center text-center h-fit">
            <h3 class="text-lg font-semibold text-gray-800 mb-2">QR Code Kendaraan</h3>
            <p class="text-sm text-gray-500 mb-6 px-4">Scan QR ini menggunakan fitur Scan di aplikasi untuk membuka data secara instan.</p>
            
            <!-- Kotak QR dengan style putus-putus -->
            <div class="bg-white p-4 rounded-xl border-2 border-dashed border-gray-300 mb-6 hover:border-blue-500 transition-colors">
                <img src="https://api.qrserver.com/v1/create-qr-code/?size=200x200&data={{ urlencode($url_kendaraan) }}" alt="QR Code {{ $vehicle->nopol }}" class="w-48 h-48">
            </div>
            
            <button onclick="window.print()" class="w-full px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg shadow-sm transition-colors flex justify-center items-center gap-2">
                <i class="fa-solid fa-print"></i> Cetak QR Code
            </button>
        </div>

    </div>
@endsection