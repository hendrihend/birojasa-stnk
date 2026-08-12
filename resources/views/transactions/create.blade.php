@extends('layouts.app')

@section('title', 'Buat Transaksi')
@section('header_title', 'Buat Transaksi Pengurusan Baru')

@section('content')
    <div class="max-w-3xl mx-auto bg-white p-8 rounded-xl shadow-sm border border-gray-100">
        
        @if($errors->any())
            <div class="mb-6 px-4 py-3 bg-red-50 border-l-4 border-red-500 text-red-700 rounded shadow-sm">
                <ul class="list-disc list-inside text-sm font-medium">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('transactions.store') }}" method="POST">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="col-span-1 md:col-span-2">
                    <label class="block text-sm font-bold text-gray-700 mb-1">Pilih Kendaraan *</label>
                    <select name="vehicle_id" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-shadow bg-gray-50 focus:bg-white appearance-none">
                        <option value="">-- Pilih Kendaraan (Nopol - Pemilik) --</option>
                        @foreach($vehicles as $veh)
                            <option value="{{ $veh->id }}" 
                                {{ (old('vehicle_id') == $veh->id || request('vehicle_id') == $veh->id) ? 'selected' : '' }}>
                                {{ $veh->nopol }} - {{ $veh->client->nama_lengkap ?? 'Tanpa Pemilik' }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-span-1 md:col-span-2">
                    <label class="block text-sm font-bold text-gray-700 mb-1">Jenis Layanan Jasa *</label>
                    <select name="jenis_layanan" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-shadow bg-gray-50 focus:bg-white appearance-none">
                        <option value="Pajak Tahunan">Pajak Tahunan</option>
                        <option value="Pajak 5 Tahunan (Ganti Kaleng)">Pajak 5 Tahunan (Ganti Kaleng)</option>
                        <option value="Mutasi Masuk/Keluar">Mutasi Masuk/Keluar</option>
                        <option value="Balik Nama">Balik Nama</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-1">Total Biaya (Rp) *</label>
                    <input type="number" name="total_biaya" value="{{ old('total_biaya', 0) }}" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-shadow bg-gray-50 focus:bg-white">
                </div>

                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-1">Tanggal Masuk Berkas *</label>
                    <input type="date" name="tgl_masuk" value="{{ old('tgl_masuk', date('Y-m-d')) }}" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-shadow bg-gray-50 focus:bg-white">
                </div>

            </div>
            <div class="mt-8 flex gap-3">
                <button type="submit" class="px-6 py-2.5 bg-gray-900 hover:bg-gray-800 text-white font-bold rounded-lg shadow-sm transition-colors">Simpan Transaksi</button>
                <a href="{{ route('transactions.index') }}" class="px-6 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 font-bold rounded-lg transition-colors">Batal</a>
            </div>
        </form>
    </div>
@endsection
