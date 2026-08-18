<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Vehicle;
use App\Models\STNKRecord;
use App\Models\Transaction;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index(Request $request) 
    {
        // ringkasan data top cards
        $totalKendaraan = Vehicle::count();
        $stnkAktif = STNKRecord::where('status_aktif', true)->count();

        $kendaraanDiproses = Transaction::whereNotIn('status_proses', ['Pending'])->count();
        $kendaraanSelesaiDiproses = Transaction::where('status_proses', ['Done'])->count();
        
        $totalPengeluaran = Transaction::sum('total_biaya');


        // B. DATA MONITORING (Pengingat Jatuh Tempo)
        $today = Carbon::today();
        $batasWaktu = Carbon::today()->addDays(90); // Ambil data H-90 ke bawah

        // Mengambil STNK yang aktif dan jatuh temponya kurang dari atau sama dengan 90 hari ke depan
        $stnkJatuhTempo = STNKRecord::with('vehicle') // Eager loading relasi kendaraan
            ->where('status_aktif', true)
            ->where('tgl_jatuh_tempo_pajak', '<=', $batasWaktu)
            ->orderBy('tgl_jatuh_tempo_pajak', 'asc')
            ->get();

        // Mapping (mengubah) format data agar mudah dibaca di View HTML
        $alerts = $stnkJatuhTempo->map(function($stnk) use ($today) {
            $jatuhTempo = Carbon::parse($stnk->tgl_jatuh_tempo_pajak);
            
            // Hitung selisih hari (false = membiarkan angka minus jika sudah lewat)
            $selisihHari = $today->diffInDays($jatuhTempo, false); 

            // Tentukan label dan warna notifikasi
            if ($selisihHari < 0) {
                $statusTeks = 'Sudah Expired / Terlambat';
                $kategoriWarna = 'danger'; // Merah
            } elseif ($selisihHari == 0) {
                $statusTeks = 'Hari H (Jatuh Tempo Hari Ini)';
                $kategoriWarna = 'danger'; // Merah
            } elseif ($selisihHari <= 14) {
                $statusTeks = 'H-' . $selisihHari;
                $kategoriWarna = 'warning'; // Kuning
            } else {
                $statusTeks = 'H-' . $selisihHari;
                $kategoriWarna = 'info'; // Biru
            }

            return (object) [
                'nopol' => $stnk->vehicle->nopol,
                'tipe' => $stnk->vehicle->tipe,
                'layanan' => 'Pajak Tahunan', // Ini bisa dibuat dinamis jika memantau pajak 5 tahunan juga
                'status_teks' => $statusTeks,
                'kategori_warna' => $kategoriWarna,
                'tanggal_asli' => $jatuhTempo->format('d M Y'),
                'vehicle_id' => $stnk->vehicle_id,

            ];
        });

        // fitur pencarian
        $search = $request->input('search');
        $searchResults = collect(); // default: data kosong

        // cari jika ada input berdasarkan nopol atau nama klien
        if ($search) {
            $searchResults = Vehicle::with('client')
            ->where('nopol', 'LIKE', "%{$search}%")
            ->orWhereHas('client', function ($query) use ($search) {
                $query->where('nama_lengkap', 'LIKE', "%{$search}%");
            })->take(10)->get();
        }

        // C. MENGIRIM DATA KE VIEW
        return view('dashboard', compact(
            'totalKendaraan',
            'stnkAktif',
            'kendaraanDiproses',
            'kendaraanSelesaiDiproses',
            'totalPengeluaran',
            'alerts', 'searchResults', 'search'
        ));

    }
}