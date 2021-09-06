<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Paket extends Model
{
    protected $table = 'pakets';
    protected $fillable = [
        'kode',
        'nama',
        'musim',
        'jml_hari',
        'tgl_mulai',
        'tgl_berakhir',
        'id_hotel',
        'id_maskapai',
        'status'
    ];

    public function hotel()
    {
        return $this->hasOne(Hotel::class, 'id', 'id_hotel');
    }

    public function maskapai()
    {
        return $this->hasOne(Maskapai::class, 'id', 'id_maskapai');
    }

    public function tipe(){
        return $this->hasMany(Tipe::class, 'id_paket', 'id');
    }

    public function photo(){
        return $this->hasMany(FotoPaket::class, 'id_paket', 'id');
    }
}
