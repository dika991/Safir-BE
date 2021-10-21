<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PemesananDetails extends Model
{
    protected $table = "pemesanan_details";
    protected $fillable = [
        "id_pemesanan",
        "id_paket",
        "id_tipe",
        "total_jemaah"
    ];


    public function pemesanan()
    {
        return $this->hasOne(Pemesanan::class, 'id', 'id_pemesanan');
    }

    public function paket(){
        return $this->hasOne(Paket::class, "id", "id_paket");
    }

    public function tipe(){
        return $this->hasOne(Tipe::class, "id", "id_tipe");
    }
}