@extends('layouts.app')
@section('title', 'Status Pengurusan dan Transaksi')
@section('header_title', 'Manjemen Transaksi dan Status Pengurusan')
@section('content')
<div style="background: #fff; padding: 20px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.05);">
        
        @if(session('success'))
            <div style="background: #d4edda; color: #155724; padding: 10px; margin-bottom: 15px; border-radius: 4px;">
                {{ session('success') }}
            </div>
        @endif

        <a href="{{ route('transactions.create') }}" style="display: inline-block; margin-bottom: 20px; padding: 10px 15px; background: #111; color: #fff; text-decoration: none; border-radius: 4px;">+ Buat Transaksi Baru</a>

        <table style="width: 100%; border-collapse: collapse; text-align: left; font-size: 14px;">
            <thead>
                <tr style="border-bottom: 2px solid #eee;">
                    <th style="padding: 12px 0;">No Invoice</th>
                    <th>Kendaraan & Klien</th>
                    <th>Layanan</th>
                    <th>Total Biaya</th>
                    <th>Status Proses</th>
                    <th>Tgl Masuk</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($transactions as $trx)
                <tr style="border-bottom: 1px solid #eee;">
                    <td style="padding: 12px 0; font-weight: bold; color: #333;">{{ $trx->invoice_no }}</td>
                    
                    <td>
                        <strong>{{ $trx->vehicle->nopol ?? '-' }}</strong><br>
                        <span style="font-size: 12px; color: #666;">{{ $trx->vehicle->client->nama_lengkap ?? '-' }}</span>
                    </td>
                    
                    <td>{{ $trx->jenis_layanan }}</td>
                    <td>Rp {{ number_format($trx->total_biaya, 0, ',', '.') }}</td>
                    
                    <!-- Label Status yang menonjol -->
                    <td>
                        <span style="padding: 4px 8px; border-radius: 4px; font-weight: bold; font-size: 12px;
                            {{ $trx->status_proses == 'Selesai' ? 'background: #d4edda; color: #155724;' : 'background: #fff3cd; color: #856404;' }}">
                            {{ $trx->status_proses }}
                        </span>
                    </td>
                    
                    <td>{{ \Carbon\Carbon::parse($trx->tgl_masuk)->format('d/m/Y') }}</td>
                    <td>
                        <a href="{{ route('transactions.edit', $trx->id) }}" style="color: #3498db; text-decoration: none; margin-right: 10px;">Update Status</a>
                        
                        <form action="{{ route('transactions.destroy', $trx->id) }}" method="POST" style="display:inline;" onsubmit="return confirm('Hapus transaksi ini?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" style="color: #e74c3c; background: none; border: none; cursor: pointer;">Hapus</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" style="text-align: center; padding: 20px; color: #666;">Belum ada transaksi.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

@endsection
