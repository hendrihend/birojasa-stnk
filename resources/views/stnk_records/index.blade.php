@extends('layouts.app')

@section('title', 'Manajemen Pajak & STNK')
@section('header_title', 'Jadwal Jatuh Tempo Pajak')

@section('content')
    <div style="background: #fff; padding: 20px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.05);">
        
        @if(session('success'))
            <div style="background: #d4edda; color: #155724; padding: 10px; margin-bottom: 15px; border-radius: 4px;">{{ session('success') }}</div>
        @endif

        <a href="{{ route('stnk_records.create') }}" style="display: inline-block; margin-bottom: 20px; padding: 10px 15px; background: #111; color: #fff; text-decoration: none; border-radius: 4px;">+ Tambah Data STNK/Pajak</a>

        <table style="width: 100%; border-collapse: collapse; text-align: left; font-size: 14px;">
            <thead>
                <tr style="border-bottom: 2px solid #eee;">
                    <th style="padding: 12px 0;">Kendaraan</th>
                    <th>Nomor STNK</th>
                    <th>Jatuh Tempo Pajak</th>
                    <th>Jatuh Tempo Kaleng</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($records as $record)
                
                <!-- Logika Perhitungan Hari -->
                @php
                    $tglPajak = \Carbon\Carbon::parse($record->tgl_jatuh_tempo_pajak);
                    $hariIni = \Carbon\Carbon::now()->startOfDay();
                    $sisaHari = $hariIni->diffInDays($tglPajak, false);
                    
                    // Menentukan Warna Baris
                    $bgColor = '';
                    if($sisaHari < 0) {
                        $bgColor = '#fff3f3'; // Merah Muda (Terlewat)
                    } elseif ($sisaHari <= 30) {
                        $bgColor = '#fffdf0'; // Kuning Muda (Mendekati)
                    }
                @endphp

                <tr style="border-bottom: 1px solid #eee; background: {{ $bgColor }};">
                    <td style="padding: 12px 5px;">
                        <strong>{{ $record->vehicle->nopol ?? '-' }}</strong><br>
                        <span style="color: #666; font-size: 12px;">{{ $record->vehicle->client->nama_lengkap ?? '-' }}</span>
                    </td>
                    <td>{{ $record->no_stnk }}</td>
                    
                    <!-- Kolom Jatuh Tempo Pajak Tahunan -->
                    <td>
                        <strong style="color: {{ $sisaHari < 0 ? '#e74c3c' : ($sisaHari <= 30 ? '#f39c12' : '#27ae60') }};">
                            {{ $tglPajak->format('d M Y') }}
                        </strong>
                        <br>
                        <span style="font-size: 12px; color: #666;">
                            {{ $sisaHari < 0 ? 'Terlewat ' . abs($sisaHari) . ' hari' : ($sisaHari == 0 ? 'Hari ini!' : $sisaHari . ' hari lagi') }}
                        </span>
                    </td>
                    
                    <td>{{ \Carbon\Carbon::parse($record->tgl_habis_stnk)->format('d M Y') }}</td>
                    
                    <td>
                        <span style="padding: 4px 8px; border-radius: 4px; font-size: 12px; background: {{ $record->status_aktif ? '#d4edda' : '#f8d7da' }}; color: {{ $record->status_aktif ? '#155724' : '#721c24' }};">
                            {{ $record->status_aktif ? 'Aktif' : 'Nonaktif' }}
                        </span>
                    </td>
                    <td>
                        <form action="{{ route('stnk_records.destroy', $record->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus data STNK ini?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" style="color: #e74c3c; background: none; border: none; cursor: pointer;">Hapus</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr><td colspan="6" style="text-align: center; padding: 20px; color: #666;">Belum ada data STNK/Pajak.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection