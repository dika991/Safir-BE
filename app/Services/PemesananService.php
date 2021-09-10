<?php

namespace App\Services;

use App\Models\Jemaah;
use App\Models\Pemesanan;
use App\Models\Tipe;
use App\Models\Transaksi;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PemesananService extends ResultService{
    private $pemesanan;
    private $jemaah;
    private $paket;
    private $transaksi;


    public function __construct(
        Tipe $paket,
        Pemesanan $pemesanan,
        Jemaah $jemaah,
        Transaksi $transaksi
    )
    {
        $this->pemesanan = $pemesanan;
        $this->jemaah = $jemaah;
        $this->paket = $paket;
        $this->transaksi = $transaksi;
    }

    public function store(array $data){
        DB::beginTransaction();
        $book = $data['book'];
        $totalPrice = 0;
        foreach($book as $b){
            $tipe = Tipe::findOrFail($b['id_paket']);
            if($tipe) $totalPrice += $tipe->harga;
        }
        $arrJemaah = [];
        try{
            $transaksi = $this->transaksi;
            $transaksi->tanggal = Carbon::create(today()->format('Y-m-d'));
            $transaksi->total_harga = $totalPrice;
            $transaksi->save();

            $pemesanan = $this->pemesanan;
            $pemesanan->nama = $data['nama'];
            $pemesanan->alamat = $data['alamat'];
            $pemesanan->email = $data['email'];
            $pemesanan->no_hp = $data['no_hp'];
            $pemesanan->catatan = $data['catatan'];
            if(isset($data['referal'])) $pemesanan->referal = $data['referal'];
            $pemesanan->id_transaksi = $transaksi->id;
            $pemesanan->save();

            foreach($data['book'] as $book){
                $paket = $this->paket->find($book['id_paket']);
                $paket->kuota = $paket->kuota - 1;
                $paket->save();
                
                $jemaah = new $this->jemaah;
                $jemaah->nama = $book['nama'];
                $jemaah->jenis_kelamin = $book['jenis_kelamin'];
                $jemaah->usia = $book['usia'];
                $jemaah->id_pemesanan = $pemesanan->id;
                $jemaah->id_paket = $paket->id;
                $jemaah->save();
                array_push($arrJemaah, ["jemaah" => $jemaah, "paket" => $paket]);
            }

            DB::commit();
            $data = [
                "transaksi" => $transaksi,
                "pemesanan" => $pemesanan,
                "book" => $arrJemaah
            ];
            return $this->setResult($data)->setFail(false);
        }catch(\Exception $e){
            Log::error($e->getMessage());
            DB::rollBack();
            return $this->setMessage($e->getMessage())->setFail(true);
        }
    }
}