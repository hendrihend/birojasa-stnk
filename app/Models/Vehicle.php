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

    // relasi ke model stnk
    public function stnk()
    {
        // vehicle memiliki 1 STNKRecord
        return $this->hasOne(STNKRecord::class, 'vehicle_id', 'id');
    }

    // relasi ke model Transaction
    public function transactions()
    {
        return $this->HasMany(Transaction::class);
    }

    public function documents()
    {
        return $this->hasMany(Document::class, 'vehicle_id', 'id');
    }
}
