<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vehicle extends Model
{
    use HasFactory;
    protected $fillable = [
        'client_id',
        'nopol',
        'no_rangka',
        'no_mesin',
        'merk',
        'tipe',
        'tahun_pembuatan',
        'warna',
        'nama_pemilik'
    ];

    // relasi ke model Client
    public function client()
    {
        return $this->belongsTo(Client::class, 'client_id');
    }
}
