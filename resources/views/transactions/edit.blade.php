@extends('layouts.app')

@section('title', 'Update Status Transaksi')
@section('header_title', 'Update Status & Biaya Transaksi')

@section('content')
    <div style="background: #fff; padding: 20px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.05); max-width: 600px;">
        
        <div style="background: #f8f9fa; padding: 15px; border-radius: 4px; margin-bottom: 20px;">
            <strong>No Invoice:</strong> {{ $transaction->invoice_no }} <br>
            <strong>Kendaraan:</strong> {{ $transaction->vehicle->nopol ?? '-' }} ({{ $transaction->vehicle->client->nama_lengkap ?? '-' }}) <br>
            <strong>Layanan:</strong> {{ $transaction->jenis_layanan }}
        </div>

        <form action="{{ route('transactions.update', $transaction->id) }}" method="POST">
            @csrf
            @method('PUT')
            
            <div style="margin-bottom: 15px;">
                <label style="display: block; margin-bottom: 5px; font-weight: bold;">Status Proses *</label>
                <select name="status_proses" required style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 4px;">
                    @foreach($statuses as $status)
                        <option value="{{ $status }}" {{ $transaction->status_proses == $status ? 'selected' : '' }}>
                            {{ $status }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div style="margin-bottom: 15px;">
                <label style="display: block; margin-bottom: 5px; font-weight: bold;">Total Biaya (Rp) *</label>
                <input type="number" name="total_biaya" value="{{ old('total_biaya', $transaction->total_biaya) }}" required style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 4px;">
            </div>

            <div style="margin-bottom: 20px;">
                <label style="display: block; margin-bottom: 5px; font-weight: bold;">Tanggal Selesai (Opsional)</label>
                <input type="date" name="tgl_selesai" value="{{ old('tgl_selesai', $transaction->tgl_selesai) }}" style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 4px;">
                <small style="color: #666;">Jika status diubah ke 'Selesai' dan ini dikosongkan, tanggal otomatis diisi hari ini.</small>
            </div>

            <button type="submit" style="padding: 10px 20px; background: #111; color: #fff; border: none; border-radius: 4px; cursor: pointer;">Simpan Perubahan</button>
            <a href="{{ route('transactions.index') }}" style="margin-left: 15px; color: #666; text-decoration: none;">Batal</a>
        </form>
    </div>
@endsection
