<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class STNKRecord extends Model
{
    use HasFactory; 
    
    protected $table = 'stnk_records';
    protected $fillable = [
        'vehicle_id',
        'no_stnk',
        'tgl_jatuh_tempo_pajak',
        'tgl_habis_stnk',
        'status_aktif',
    ];

    public function vehicle()
    {
        return $this->belongsTo(Vehicle::class, 'vehicle_id', 'id');
    }
}
