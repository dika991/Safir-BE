<?php

namespace App\Models;

use App\Models\Pembayaran;
use App\Models\Transaksi;
use App\Models\Jemaah;
use App\Models\Tagihan;

use Illuminate\Database\Eloquent\Model;

class Pemesanan extends Model
{
    protected $table = "pemesanans";
    protected $fillable = [
        "nama",
        "alamat",
        "email",
        "no_hp",
        "catatan",
        "referal",
        "id_transaksi",
        "code",
        "user_id",
        "pemesanan"
    ];

    // protected $appends = [
    //     "parent_paket"
    // ];

    public function detail(){
        return $this->hasMany(PemesananDetails::class, 'id_pemesanan', 'id');
    }
    public function jemaah()
    {
        return $this->hasMany(Jemaah::class, 'id_pemesanan', 'id');
    }

    public function transaksi()
    {
        return $this->hasOne(Transaksi::class, 'id', 'id_transaksi');
    }

    public function pembayaran()
    {
        return $this->hasMany(Pembayaran::class, 'id', 'id_pemesanan');
    }
    
    public function tagihan(){
        return $this->hasMany(Tagihan::class, 'id_pemesanan', 'id');
    }

    public function getParentPaketAttribute(){
        $tipe = Jemaah::where('id_pemesanan', $this->id)->with('tipe')->first();
        $paket = Paket::where('id', $tipe->tipe->id_paket)->first();
        return $paket;
    }

}
