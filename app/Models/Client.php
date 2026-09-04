<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    use HasFactory;
    protected $fillable = [
        'nik',
        'nama_lengkap',
        'no_whatsapp',
        'email',
        'alamat',
    ];
}
