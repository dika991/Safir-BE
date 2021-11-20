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

    public function pembayaran(){
        return $this->hasOne(Pembayaran::class, 'id_tagihan', 'id');
    }

    public function pictures()
    {
        return $this->hasOne(PembayaranPictures::class, "id_tagihan", "id");
    }
}
