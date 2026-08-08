@extends('layouts.app')
@section('title', 'Edit Klien')
@section('header_title', 'Edit Data Klien')

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

        <!-- Form mengarah ke route update dan membawa ID klien -->
        <form action="{{ route('clients.update', $client->id) }}" method="POST">
            @csrf
            @method('PUT') <!-- Wajib untuk proses update di Laravel -->

            <div style="margin-bottom: 15px;">
                <label style="display: block; margin-bottom: 5px; font-weight: bold;">Nama Lengkap (Wajib)</label>
                <input type="text" name="nama_lengkap" value="{{ old('nama_lengkap', $client->nama_lengkap) }}" required style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 4px;">
            </div>

            <div style="margin-bottom: 15px;">
                <label style="display: block; margin-bottom: 5px; font-weight: bold;">NIK KTP</label>
                <input type="text" name="nik" value="{{ old('nik', $client->nik) }}" style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 4px;">
            </div>

            <div style="margin-bottom: 15px;">
                <label style="display: block; margin-bottom: 5px; font-weight: bold;">Nomor WhatsApp</label>
                <input type="text" name="no_whatsapp" value="{{ old('no_whatsapp', $client->no_whatsapp) }}" style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 4px;">
            </div>

            <div style="margin-bottom: 20px;">
                <label style="display: block; margin-bottom: 5px; font-weight: bold;">Alamat Domisili</label>
                <textarea name="alamat" rows="4" style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 4px;">{{ old('alamat', $client->alamat) }}</textarea>
            </div>

            <button type="submit" style="padding: 10px 20px; background: #111; color: #fff; border: none; border-radius: 4px; cursor: pointer;">Update Data</button>
            <a href="{{ route('clients.index') }}" style="margin-left: 15px; color: #666; text-decoration: none;">Batal</a>
        </form>
    </div>

@endsection
