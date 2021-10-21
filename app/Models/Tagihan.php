<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tagihan extends Model
{
    use HasFactory;


    protected $fillable =
    [
        'id_pemesanan',
        'deskripsi',
        'tipe',
        'limit_at',
        'nominal',
        'status'
    ];

    public function pemesanan()
    {
        return $this->hasOne(Pemesanan::class, 'id', 'id_pemesanan');
    }
}
