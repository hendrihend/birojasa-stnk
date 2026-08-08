<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Document extends Model
{
    use HasFactory;

    protected $fillable = [
        'vehicle_id',
        'jenis_dokumen',
        'file_path',
    ];

    // dokumen dimiliki oleh 1 kendaraan
    public function vehicle()
    {
        return $this->belongsTo(Vehicle::class);
    }
}
