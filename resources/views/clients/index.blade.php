@extends('layouts.app')
@section('header_title', 'Daftar Klien')
@section('content')

<div style="background: #fff; padding: 20px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.05);">
        
        <!-- Pesan Sukses -->
        @if(session('success'))
            <div style="background: #d4edda; color: #155724; padding: 10px; margin-bottom: 15px; border-radius: 4px;">
                {{ session('success') }}
            </div>
        @endif

        <a href="{{ route('clients.create') }}" style="display: inline-block; margin-bottom: 20px; padding: 10px 15px; background: #111; color: #fff; text-decoration: none; border-radius: 4px;">+ Tambah Klien Baru</a>

        <table style="width: 100%; border-collapse: collapse; text-align: left;">
            <thead>
                <tr style="border-bottom: 2px solid #eee;">
                    <th>No</th>
                    <th>Nama Lengkap</th>
                    <th>NIK</th>
                    <th>No WhatsApp</th>
                    <th>Alamat</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($clients as $index => $client)
                <tr style="border-bottom: 1px solid #eee;">
                    <td style="padding: 12px 0;">{{ $index + 1 }}</td>
                    <td style="font-weight: bold; text-transform: uppercase;">{{ $client->nama_lengkap }}</td>
                    <td>{{ $client->nik ?? '-' }}</td>
                    <td>{{ $client->no_whatsapp ?? '-' }}</td>
                    <td>{{ $client->alamat }}</td>
                    <td>
                        <a href="{{ route('clients.edit', $client->id) }}" style="color: #3498db; text-decoration: none; margin-right: 10px;">Edit</a> 
                        
                        <form action="{{ route('clients.destroy', $client->id) }}" method="POST" style="display:inline;" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data kendaraan ini?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" style="color: #e74c3c; background: none; border: none; cursor: pointer; font-size: 16px;">Hapus</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" style="text-align: center; padding: 20px; color: #666;">Belum ada data Klien.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

@endsection