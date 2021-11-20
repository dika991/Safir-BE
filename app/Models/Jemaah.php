<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use function PHPSTORM_META\map;

class Jemaah extends Model
{
    
    protected $table = "jemaahs";

    protected $fillable = [
        "nama",
        "jenis_kelamin",
        "usia",
        "id_pemesanan",
        "id_paket"
    ];

    public function tipe(){
        return $this->hasOne(Tipe::class, 'id', 'id_paket');
    }
}
