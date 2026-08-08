<!-- Master Layout -->
@extends('layouts.app')

<!-- title -->
@section('title', 'Dashboard')

<!-- Header Content -->
@section('header_title', 'Dashboard')

  <!-- Main Content -->
  @section('content')
    <!-- Bagian Top Cards -->
    <section class="summary-cards">
        <div class="card">
            <h3>Total Kendaraan</h3>
            <p class="number">{{ number_format($totalKendaraan, 0, ',', '.') }}</p>
        </div>
        <div class="card">
            <h3>STNK Aktif</h3>
            <p class="number">{{ number_format($stnkAktif, 0, ',', '.') }}</p>
        </div>
        <div class="card">
            <h3>Kendaraan Diproses</h3>
            <p class="number">{{ $kendaraanDiproses }}</p>
        </div>
        <div class="card highlight">
            <h3>Total Pengeluaran</h3>
            <p class="number">Rp {{ number_format($totalPengeluaran, 0, ',', '.') }}</p>
        </div>
    </section>

    <!-- Bagian Monitoring / Pengingat -->
    <section class="monitoring-section">
        <h2>Peringatan Jatuh Tempo</h2>
        <div class="alert-grid">
            
            @forelse($alerts as $alert)
                <!-- Class warna (danger/warning/info) dipanggil dinamis dari Controller -->
                <div class="alert-item {{ $alert->kategori_warna }}">
                    <div class="alert-info">
                        <strong>{{ $alert->nopol }} ({{ $alert->tipe }})</strong>
                        <span>{{ $alert->layanan }} - {{ $alert->status_teks }} ({{ $alert->tanggal_asli }})</span>
                    </div>
                    <!-- <button class="btn-action"></button> -->
                     <a href="{{ route('transactions.create', ['vehicle_id' => $alert->vehicle_id]) }}" style="padding: 5px 10px; background: #e74c3c; color: #fff; text-decoration: none; border-radius: 4px; font-size: 12px;">Urus Sekarang</a>
                </div>
            @empty
                <div style="padding: 20px; text-align: center; color: #666; background: #fff; border-radius: 6px;">
                    Tidak ada STNK yang mendekati jatuh tempo dalam 90 hari ke depan.
                </div>
            @endforelse

        </div>
    </section>
    @endsection

   