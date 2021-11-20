<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PembayaranPictures extends Model
{
    protected $table = "pembayaran_pictures";

    protected $fillable = [
        "id_pemesanan",
        "name",
        "url",
        "path"
    ];
}