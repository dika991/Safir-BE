<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Hotel extends Model
{
    /**
     * @var string
     */
    protected $table = 'hotels';

    protected $fillable = [
        'name',
        'alamat',
        'lokasi'
    ];

    protected $hidden = [
        'created_at',
        'updated_at'
    ];
}
