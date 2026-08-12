@extends('layouts.app')

@section('title', 'Update Status Transaksi')
@section('header_title', 'Update Status & Biaya Transaksi')

@section('content')
    <div class="max-w-3xl mx-auto bg-white p-8 rounded-xl shadow-sm border border-gray-100">

        
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden mb-8">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50 border-b border-gray-200 text-sm text-gray-600 uppercase tracking-wider">
                        <th class="p-4">No Invoice</th>
                        <th>:</th>
                        <td>{{ $transaction->invoice_no }}</td>
                    </tr>
                    <tr class="bg-gray-50 border-b border-gray-200 text-sm text-gray-600 uppercase tracking-wider">
                        <th class="p-4">Kendaraan</th>
                        <th>:</th>
                        <td>{{ $transaction->vehicle->nopol ?? '-' }}</td>
                    </tr>
                    <tr class="bg-gray-50 border-b border-gray-200 text-sm text-gray-600 uppercase tracking-wider">
                        <th class="p-4">Layanan</th>
                        <th>:</th>
                        <td>{{ $transaction->jenis_layanan }}</td>
                    </tr>
                    <tr class="bg-gray-50 border-b border-gray-200 text-sm text-gray-600 uppercase tracking-wider">
                        <th class="p-4">Nama Klien</th>
                        <th>:</th>
                        <td>{{ $transaction->vehicle->client->nama_lengkap ?? '-' }}</td>
                    </tr>
                </thead>
            </table>
        </div>

        <form action="{{ route('transactions.update', $transaction->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            
                <div class="col-span-1 md:col-span-2">
                    <label class="block text-sm font-bold text-gray-700 mb-1">Status Proses *</label>
                    <select name="status_proses" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-shadow bg-gray-50 focus:bg-white appearance-none">
                        @foreach($statuses as $status)
                            <option value="{{ $status }}" {{ $transaction->status_proses == $status ? 'selected' : '' }}>
                                {{ $status }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-span-1 md:col-span-2">
                    <label class="block text-sm font-bold text-gray-700 mb-1">Total Biaya (Rp) *</label>
                    <input type="number" name="total_biaya" value="{{ old('total_biaya', $transaction->total_biaya) }}" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-shadow bg-gray-50 focus:bg-white appearance-none">
                </div>

                <div class="col-span-1 md:col-span-2">
                    <label class="block text-sm font-bold text-gray-700 mb-1">Tanggal Selesai (Opsional)</label>
                    <input type="date" name="tgl_selesai" value="{{ old('tgl_selesai', $transaction->tgl_selesai) }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-shadow bg-gray-50 focus:bg-white appearance-none">
                    <small class="text-xs text-gray-500 mt-1">Jika status diubah ke 'Selesai' dan ini dikosongkan, tanggal otomatis diisi hari ini.</small>
                </div>
            </div>

            <div class="mt-8 flex gap-3">
                <button type="submit" class="px-6 py-2.5 bg-gray-900 hover:bg-gray-800 text-white font-bold rounded-lg shadow-sm transition-colors">Simpan Perubahan</button>
                <a href="{{ route('transactions.index') }}" class="px-6 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 font-bold rounded-lg transition-colors">Batal</a>
            </div>

        </form>
    </div>
@endsection
