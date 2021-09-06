<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Maskapai extends Model
{
    protected $table = 'maskapais';

    protected $fillable = [
        'kode_maskapai',
        'nama'
    ];

    protected $hidden = [
        'created_at',
        'updated_at'
    ];
}
