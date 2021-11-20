<?php

namespace App\Transformers;

use Illuminate\Support\Facades\Log;

class TransaksiTransformer
{
    public function transform($data){
        return [
            "id" => $data->id,
            "tanggal" => $data->tanggal,
            "kode_voucher" => $data->kode_voucher,
            "total_harga" => $data->total_harga,
            "sudah_dibayar" => $data->sudah_dibayar,
            "belum_dibayar" => $data->belum_dibayar
        ];
    }

    public function paginator($data){
        $data->getCollection()->transform(function ($items, $key){
            return [
                "id" => $items->id,
                "tanggal" => $items->tanggal,
                "kode_voucher" => $items->kode_voucher,
                "total_harga" => $items->total_harga,
                "sudah_dibayar" => $items->sudah_dibayar,
                "belum_dibayar" => $items->belum_dibayar
            ];
        });
        return $data;
    }
}