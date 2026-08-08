@extends('layouts.app')
@section('title', 'Data Kendaraan')
@section('header_title', 'Manajemen Data Kendaraan')

@section('content')
<div style="background: #fff; padding: 20px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.05);">
        
        <!-- Pesan Sukses -->
        @if(session('success'))
            <div style="background: #d4edda; color: #155724; padding: 10px; margin-bottom: 15px; border-radius: 4px;">
                {{ session('success') }}
            </div>
        @endif

        <a href="{{ route('vehicles.create') }}" style="display: inline-block; margin-bottom: 20px; padding: 10px 15px; background: #111; color: #fff; text-decoration: none; border-radius: 4px;">+ Tambah Kendaraan Baru</a>

        <table style="width: 100%; border-collapse: collapse; text-align: left;">
            <thead>
                <tr style="border-bottom: 2px solid #eee;">
                    <th style="padding: 12px 0;">No</th>
                    <th>Nomor Polisi</th>
                    <th>Nama Klien</th>
                    <th>Nama Pemilik</th>
                    <th>Merk & Tipe</th>
                    <th>Tahun Pembuatan</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($vehicles as $index => $vehicle)
                <tr style="border-bottom: 1px solid #eee;">
                    <td style="padding: 12px 0;">{{ $index + 1 }}</td>
                    <td style="font-weight: bold; text-transform: uppercase;">{{ $vehicle->nopol }}</td>
                    
                    <!-- Memanggil relasi data Klien -->
                    <td>{{ $vehicle->client->nama_lengkap ?? 'Data Tidak Ditemukan' }}</td>
                    <td>{{ $vehicle->nama_pemilik ?? 'Data Tidak Ditemukan' }}</td>
                    
                    <td>{{ $vehicle->merk }} {{ $vehicle->tipe }}</td>
                    <td>{{ $vehicle->tahun_pembuatan ?? '-' }}</td>
                    <td>
                        <a href="{{ route('documents.index', $vehicle->id) }}" style="color: #27ae60; text-decoration: none; margin-right: 10px; font-weight:bold;">Arsip Dokumen</a>
                        <a href="{{ route('vehicles.edit', $vehicle->id) }}" style="color: #3498db; text-decoration: none; margin-right: 10px;">Edit</a> 
                        
                        <form action="{{ route('vehicles.destroy', $vehicle->id) }}" method="POST" style="display:inline;" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data kendaraan ini?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" style="color: #e74c3c; background: none; border: none; cursor: pointer; font-size: 16px;">Hapus</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" style="text-align: center; padding: 20px; color: #666;">Belum ada data kendaraan.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection