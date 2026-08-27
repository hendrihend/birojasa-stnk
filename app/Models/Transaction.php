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
        'biaya_lain',
        'total_biaya',
        'tgl_masuk',
        'tgl_selesai',
    ];
    // relasi ke tabel kendaraan (1 transaksi dimiliki oleh 1 kendaraan
    public function vehicle()
    {
        return $this->belongsTo(Vehicle::class, 'vehicle_id');
    }
}