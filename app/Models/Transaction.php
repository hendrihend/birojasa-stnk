<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;
    protected $fillable = [
        'invoice_no',
        'vehicle_id',
        'jenis_layanan',
        'status_proses',
        'biaya_pajak',
        'biaya_jasa',
        // 8 Rincian Biaya Lain
        'loket_pendaftaran',
        'loket_cek_fisik',
        'acc_tidak_hadir',
        'acc_domisili',
        'loket_penetapan',
        'loket_pengesahan_1',
        'loket_pengesahan_2',
        'bea_materai',
        'biaya_lain', // Total keseluruhan 8 rincian biaya lain
        'total_biaya', // Total global dari seluruh biaya
        'tgl_masuk',
        'tgl_selesai',
    ];
    // relasi ke tabel kendaraan (1 transaksi dimiliki oleh 1 kendaraan
    public function vehicle()
    {
        return $this->belongsTo(Vehicle::class, 'vehicle_id');
    }
}