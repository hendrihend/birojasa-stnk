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
        // A. RINGKASAN DATA (TOP CARDS)
        $totalKendaraan = Vehicle::count();
        $stnkAktif = STNKRecord::where('status_aktif', true)->count();
        $totalPengeluaran = Transaction::where('status_proses', 'Done')->sum('total_biaya');

        $kendaraanDiproses = Transaction::where('status_proses', 'Pending')->count();
        $kendaraanSelesaiDiproses = Transaction::where('status_proses', 'Done')->count();
        $kendaraanCancel = Transaction::where('status_proses', 'Cancel')->count();

        // B. FITUR PENCARIAN CEPAT (Quick Search)
        $search = $request->input('search');
        $searchResults = collect(); // default: data kosong

        // cari jika ada input berdasarkan nopol atau nama klien
        if ($search) {
            $cleanSearch = str_replace(' ', '', $search); // untuk hapus spasi
            $searchResults = Vehicle::with('client')
                ->where('nopol', 'LIKE', "%{$search}%")
                ->orWhereRaw("REPLACE(nopol, ' ', '') LIKE ?", ["%{$cleanSearch}%"])
                ->orWhereHas('client', function ($query) use ($search) {
                    $query->where('nama_lengkap', 'LIKE', "%{$search}%");
                })->take(10)->get();
        }

        // C. DATA MONITORING (Pajak Tahunan & 5 Tahunan)
        $today = Carbon::today();
        $batasWaktu = Carbon::today()->addDays(90); // Ambil data H-90 ke bawah

        // Ambil STNK aktif beserta TRANSAKSI TERBARU saja
        $stnkRecords = STNKRecord::with(['vehicle.transactions' => function($query) {
                $query->latest();
            }]) // Eager loading relasi kendaraan
            ->where('status_aktif', true)
            ->where(function($query) use ($batasWaktu){
                // Jangan ambil data jika pajaknya masih jauh (> 90 hari)
                $query  ->where('tgl_jatuh_tempo_pajak', '<=', $batasWaktu)
                        ->orWhere('tgl_habis_stnk', '<=', $batasWaktu);
            })->get();

        $alerts = collect(); // wadah kosong untuk notif
        foreach ($stnkRecords as $stnk) {
            if (!$stnk->vehicle) continue; //keamanan jika data kendaraan terhapus
            $transaksiTerakhir = $stnk->vehicle->transactions->first();
            $sedangDiurus = false;

            // LOGIKA TRANSAKSI (Pending vs Done)
            if ($transaksiTerakhir) {
                // SYARAT 4: Jika status transaksi terakhir adalah DONE (dalam 90 hari terakhir)
                if ($transaksiTerakhir->status_proses == 'Done' && $transaksiTerakhir->update_at >= Carbon::now()->subDays(90)) {
                    continue; // LEWATI! Kendaraan ini tidak akan dimasukkan ke Dashboard (Dashboard Bersih)
                }
                // SYARAT 3: Jika statusnya bukan Done (contoh: Pending, Diproses)
                if ($transaksiTerakhir->status_proses == 'Pending') {
                    $sedangDiurus = true; // Akan memicu warna biru dan tombol "Cek Transaksi"
                }
            }
            $tipeKendaraan = $stnk->vehicle->tipe;

            // CEK PAJAK TAHUNAN
            if ($stnk->tgl_jatuh_tempo_pajak) {
                $jatuhTempo = Carbon::parse($stnk->tgl_jatuh_tempo_pajak);
                $selisihHari = $today->diffInDays($jatuhTempo, false);
                if ($selisihHari <= 90) {
                    $alerts->push(
                        $this->formatAlert($stnk, 'Pajak Tahunan',
                        $jatuhTempo, $selisihHari, $tipeKendaraan, $sedangDiurus));
                }
            }
                    
            // CEK PAJAK 5 TAHUNAN  (GANTI KALENG)
            if ($stnk->tgl_habis_stnk) {
                $jatuhTempoKaleng = Carbon::parse($stnk->tgl_habis_stnk);
                $selisihHariKaleng = $today->diffInDays($jatuhTempoKaleng, false);
                if ($selisihHariKaleng <= 90) {
                    $alerts->push(
                        $this->formatAlert($stnk, 'Ganti Kaleng (5 Tahunan)',
                        $jatuhTempoKaleng, $selisihHariKaleng, $tipeKendaraan, $sedangDiurus));
                }
            }
        }

        // Urutkan peringatan dari yang paling mendesak (angka minus / telat diurutkan paling atas)
        $alerts = $alerts->sortBy('sisa_hari')->values();

        // C. MENGIRIM DATA KE VIEW
        return view('dashboard', compact(
            'totalKendaraan',
            'stnkAktif',
            'totalPengeluaran',
            'kendaraanDiproses',
            'kendaraanSelesaiDiproses',
            'kendaraanCancel',
            'alerts', 'searchResults', 'search'
        ));

    }

    private function formatAlert($stnk, $layanan, $tanggal, $selisihHari, $tipe, $sedangDiurus) {

        // Jika sedang diurus, paksa statusnya menjadi 'info' (Biru)
        if ($sedangDiurus) {
            $statusTeks = 'Sedang Diurus ⏳';
            $kategoriWarna = 'info';
        } else {
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
                $kategoriWarna = 'aman'; // Akan memunculkan tombol Urus Sekarang
            }
        }

        return (object) [
            'nopol' => $stnk->vehicle->nopol,
            'tipe' => $tipe,
            'jenis_layanan' => $layanan,
            'status_teks' => $statusTeks,
            'kategori_warna' => $kategoriWarna,
            'tanggal_asli' => $tanggal->format('d M Y'),
            'vehicle_id' => $stnk->vehicle_id,
            'sisa_hari' => $selisihHari,
            'email' => $stnk->vehicle->client->email,
        ];
    }
}