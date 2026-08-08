@extends('layouts.app')

@section('title', 'Buat Transaksi')
@section('header_title', 'Buat Transaksi Pengurusan Baru')

@section('content')
    <div style="background: #fff; padding: 20px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.05); max-width: 600px;">
        
        @if($errors->any())
            <div style="color: red; margin-bottom: 15px;">
                <ul style="padding-left: 20px;">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('transactions.store') }}" method="POST">
            @csrf
            
            <div style="margin-bottom: 15px;">
                <label style="display: block; margin-bottom: 5px; font-weight: bold;">Pilih Kendaraan *</label>
                <select name="vehicle_id" required style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 4px;">
                    <option value="">-- Pilih Kendaraan (Nopol - Pemilik) --</option>
                    @foreach($vehicles as $veh)
                        <option value="{{ $veh->id }}" 
                            {{ (old('vehicle_id') == $veh->id || request('vehicle_id') == $veh->id) ? 'selected' : '' }}>
                            {{ $veh->nopol }} - {{ $veh->client->nama_lengkap ?? 'Tanpa Pemilik' }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div style="margin-bottom: 15px;">
                <label style="display: block; margin-bottom: 5px; font-weight: bold;">Jenis Layanan Jasa *</label>
                <select name="jenis_layanan" required style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 4px;">
                    <option value="Pajak Tahunan">Pajak Tahunan</option>
                    <option value="Pajak 5 Tahunan (Ganti Kaleng)">Pajak 5 Tahunan (Ganti Kaleng)</option>
                    <option value="Mutasi Masuk/Keluar">Mutasi Masuk/Keluar</option>
                    <option value="Balik Nama">Balik Nama</option>
                </select>
            </div>

            <div style="margin-bottom: 15px;">
                <label style="display: block; margin-bottom: 5px; font-weight: bold;">Total Biaya (Rp) *</label>
                <input type="number" name="total_biaya" value="{{ old('total_biaya', 0) }}" required style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 4px;">
            </div>

            <div style="margin-bottom: 20px;">
                <label style="display: block; margin-bottom: 5px; font-weight: bold;">Tanggal Masuk Berkas *</label>
                <input type="date" name="tgl_masuk" value="{{ old('tgl_masuk', date('Y-m-d')) }}" required style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 4px;">
            </div>

            <button type="submit" style="padding: 10px 20px; background: #111; color: #fff; border: none; border-radius: 4px; cursor: pointer;">Simpan Transaksi</button>
            <a href="{{ route('transactions.index') }}" style="margin-left: 15px; color: #666; text-decoration: none;">Batal</a>
        </form>
    </div>
@endsection
