<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaksi extends Model
{
    protected $table = 'transaksis';

    protected $fillable = [
        "tanggal",
        "kode_voucher",
        "sudah_dibayar",
        "belum_dibayar"
    ];

    public function pemesan(){
        return $this->hasOne(Pemesanan::class, 'id_transaksi', 'id');
    }

}
