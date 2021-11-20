<?php

namespace App\Services;

use App\Models\Jemaah;
use App\Models\Pemesanan;
use App\Models\PemesananDetails;
use App\Models\Tagihan;
use App\Models\Tipe;
use App\Models\Transaksi;
use App\Traits\RandomCode;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PemesananService extends ResultService
{
    use RandomCode;
    private $pemesanan;
    private $jemaah;
    private $paket;
    private $transaksi;
    private $pemesananDetails;


    public function __construct(
        Tipe $paket,
        Pemesanan $pemesanan,
        Jemaah $jemaah,
        Transaksi $transaksi,
        PemesananDetails $pemesananDetails
    ) {
        $this->pemesanan = $pemesanan;
        $this->jemaah = $jemaah;
        $this->paket = $paket;
        $this->transaksi = $transaksi;
        $this->pemesananDetails = $pemesananDetails;
    }

    public function store(array $data)
    {
        DB::beginTransaction();
        $book = $data['book'];
        $totalPrice = 0;
        foreach ($book as $b) {
            $tipe = Tipe::findOrFail($b['id_paket']);
            if ($tipe) $totalPrice += $tipe->harga;
        }
        $arrJemaah = [];
        try {
            $transaksi = $this->transaksi;
            $transaksi->tanggal = Carbon::create(today()->format('Y-m-d'));
            $transaksi->total_harga = $totalPrice;
            $transaksi->sudah_dibayar = 0;
            $transaksi->belum_dibayar = $totalPrice;
            $transaksi->save();

            $pemesanan = $this->pemesanan;
            $pemesanan->nama = $data['nama'];
            $pemesanan->alamat = $data['alamat'];
            $pemesanan->email = $data['email'];
            $pemesanan->no_hp = $data['no_hp'];
            $pemesanan->catatan = $data['catatan'];
            if (isset($data['referal'])) $pemesanan->referal = $data['referal'];
            $pemesanan->id_transaksi = $transaksi->id;
            $pemesanan->code = $this->getCodeForPemesanan();
            $pemesanan->user_id = Auth::user()->id;
            $pemesanan->save();

            foreach ($data['book'] as $book) {
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
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            DB::rollBack();
            return $this->setMessage($e->getMessage())->setFail(true);
        }
    }

    public function storeV2($data)
    {
        $book = $data['types'];
        $totalPrice = 0;
        foreach ($book as $b) {
            $tipe = Tipe::findOrFail($b['id']);
            $nominal = $tipe->harga * $b['total'];
            if ($tipe) $totalPrice += $nominal;
        }
        $detail = [];
        DB::beginTransaction();
        try {
            $transaksi = $this->transaksi;
            $transaksi->tanggal = Carbon::create(today()->format('Y-m-d'));
            $transaksi->total_harga = $totalPrice;
            $transaksi->sudah_dibayar = 0;
            $transaksi->belum_dibayar = $totalPrice;
            $transaksi->save();

            $pemesanan = $this->pemesanan;
            $pemesanan->nama = $data['nama'];
            // $pemesanan->alamat = $data['alamat'];
            $pemesanan->email = $data['email'];
            $pemesanan->no_hp = $data['no_hp'];
            // $pemesanan->catatan = $data['catatan'];
            if (isset($data['referal'])) $pemesanan->referal = $data['referal'];
            $pemesanan->id_transaksi = $transaksi->id;
            $pemesanan->code = $this->getCodeForPemesanan();
            $pemesanan->user_id = Auth::user()->id;
            $pemesanan->status = 0;
            $pemesanan->save();

            foreach ($book as $b) {
                if ($b['total'] == 0) {
                    continue;
                } else {
                    $tipe = Tipe::findOrFail($b['id']);
                    $tipe->kuota = $tipe->kuota - $b['total'];
                    $tipe->save();
                    $pemesananDet = new $this->pemesananDetails;
                    $pemesananDet->id_pemesanan = $pemesanan->id;
                    $pemesananDet->id_paket = $tipe->id_paket;
                    $pemesananDet->id_tipe = $b['id'];
                    $pemesananDet->total_jemaah = $b['total'];
                    $pemesananDet->save();
                    foreach(range(1, $b['total']) as $daa){
                        $jemaah = Jemaah::create([
                            "nama" => NULL,
                            "jenis_kelamin" => NULL,
                            "usia" => NULL,
                            "id_pemesanan" => $pemesanan->id,
                            "id_paket" => $tipe->id,
                        ]);
                    }
                    array_push($detail, $pemesananDet);
                }
            }

            $tagihan = Tagihan::create([
                "id_pemesanan" => $pemesanan->id,
                "deskripsi" => "Uang Muka",
                "tipe" => "cicilan",
                "limit_at" => Carbon::now()->addDay(7)->format('Y-m-d'),
                "nominal" => 0.1 * $totalPrice,
                "status" => 0
            ]);
            DB::commit();
            $jemaahs = Jemaah::where('id_pemesanan', $pemesanan->id)->get();

            $data = [
                "transaksi" => $transaksi,
                "pemesanan" => $pemesanan,
                "detail" => $detail,
                "jemaah" => $jemaahs
            ];
            return $this->setResult($data)->setFail(false);
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            DB::rollBack();
            return $this->setMessage($e->getMessage())->setFail(true);
        }
    }
}
