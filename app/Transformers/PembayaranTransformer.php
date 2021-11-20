<?php

namespace App\Transformers;

use Illuminate\Support\Facades\Log;

class PembayaranTransformer
{
    public function transform($data){
        return [
            "id" => $data->id,
            "pemesanan" => $data->pemesanan,
            "deskripsi" => $data->deskripsi,
            "tipe" => $data->tipe,
            "limited_at" => $data->limit_at,
            "nominal" => $data->nominal,
            "status" => $data->status,
            "pictures" => $data->pictures
        ];
    }

    public function paginator($data){
        $data->getCollection()->transform(function ($items, $key){
            return [
                "id" => $items->id,
                "pemesanan" => $items->pemesanan,
                "deskripsi" => $items->deskripsi,
                "tipe" => $items->tipe,
                "limited_at" => $items->limit_at,
                "nominal" => $items->nominal,
                "status" => $items->status
            ];
        });
        return $data;
    }
}