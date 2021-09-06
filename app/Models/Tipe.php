<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tipe extends Model
{
    protected $table = 'tipes';

    protected $fillable = [
        'nama',
        'harga',
        'kuota',
        'id_paket'
    ];
}
