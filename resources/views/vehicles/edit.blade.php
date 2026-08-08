@extends('layouts.app')
@section('title', 'Edit Kendaraan')
@section('header_title', 'Edit Data Kendaraan')

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

        <form action="{{ route('vehicles.update', $vehicle->id) }}" method="POST">
            @csrf
            @method('PUT') <!-- Wajib untuk proses update -->
            
            <div style="margin-bottom: 15px;">
                <label style="display: block; margin-bottom: 5px; font-weight: bold;">Nama Klien *</label>
                <select name="client_id" required style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 4px;">
                    <option value="">-- Pilih Klien --</option>
                    @foreach($clients as $client)
                        <option value="{{ $client->id }}" {{ (old('client_id', $vehicle->client_id) == $client->id) ? 'selected' : '' }}>
                            {{ $client->nama_lengkap }} (NIK: {{ $client->nik ?? '-' }})
                        </option>
                    @endforeach
                </select>
            </div>

            <div style="margin-bottom: 15px;">
                <label style="display: block; margin-bottom: 5px; font-weight: bold;">Nomor Polisi (Nopol) *</label>
                <input type="text" name="nopol" value="{{ old('nopol', $vehicle->nopol) }}" required style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 4px; text-transform: uppercase;">
            </div>

            <div style="display: flex; gap: 15px; margin-bottom: 15px;">
                <div style="flex: 1;">
                    <label style="display: block; margin-bottom: 5px; font-weight: bold;">No Rangka</label>
                    <input type="text" name="no_rangka" value="{{ old('no_rangka', $vehicle->no_rangka) }}" style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 4px;">
                </div>
                <div style="flex: 1;">
                    <label style="display: block; margin-bottom: 5px; font-weight: bold;">No Mesin</label>
                    <input type="text" name="no_mesin" value="{{ old('no_mesin', $vehicle->no_mesin) }}" style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 4px;">
                </div>
            </div>

            <div style="display: flex; gap: 15px; margin-bottom: 15px;">
                <div style="flex: 1;">
                    <label style="display: block; margin-bottom: 5px; font-weight: bold;">Merk</label>
                    <input type="text" name="merk" value="{{ old('merk', $vehicle->merk) }}" style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 4px;">
                </div>
                <div style="flex: 1;">
                    <label style="display: block; margin-bottom: 5px; font-weight: bold;">Tipe</label>
                    <input type="text" name="tipe" value="{{ old('tipe', $vehicle->tipe) }}" style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 4px;">
                </div>
            </div>

            <div style="display: flex; gap: 15px; margin-bottom: 15px;">
                <div style="flex: 1;">
                    <label style="display: block; margin-bottom: 5px; font-weight: bold;">Tahun Pembuatan</label>
                    <input type="text" name="tahun_pembuatan" value="{{ old('tahun_pembuatan', $vehicle->tahun_pembuatan) }}" style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 4px;">
                </div>
                <div style="flex: 1;">
                    <label style="display: block; margin-bottom: 5px; font-weight: bold;">Warna</label>
                    <input type="text" name="warna" value="{{ old('warna', $vehicle->warna) }}" style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 4px;">
                </div>
            </div>

            <div style="margin-bottom: 20px;">
                <label style="display: block; margin-bottom: 5px; font-weight: bold;">Nama Tercetak di STNK</label>
                <input type="text" name="nama_pemilik" value="{{ old('nama_pemilik', $vehicle->nama_pemilik) }}" style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 4px;">
            </div>

            <button type="submit" style="padding: 10px 20px; background: #111; color: #fff; border: none; border-radius: 4px; cursor: pointer;">Update Kendaraan</button>
            <a href="{{ route('vehicles.index') }}" style="margin-left: 15px; color: #666; text-decoration: none;">Batal</a>
        </form>
    </div>


@endsection