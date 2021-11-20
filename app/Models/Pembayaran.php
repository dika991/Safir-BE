<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pembayaran extends Model
{
    protected $table = "pembayarans";

    protected $fillable = [
        "tanggal",
        "nominal",
        "catatan",
        "id_pemesanan",
        "id_tagihan"
    ];
}
