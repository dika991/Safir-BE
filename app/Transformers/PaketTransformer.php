<?php

namespace App\Transformers;

use Illuminate\Support\Facades\Log;

class PaketTransformer
{
    public function transform($data) : array
    {
        return [
            "uid" => $data->id,
            "code" => $data->kode,
            "name" => $data->nama,
            "season" => $data->musim,
            "total_days" => $data->jml_hari,
            "start_date" => $data->tgl_mulai,
            "end_date" => $data->tgl_berakhir,
            "hotel_id" => $data->id_hotel,
            "airlines_id" => $data->id_maskapai,
            "hotel" => $data->hotel,
            "airlines" => $data->maskapai,
            "types" => $data->tipe,
            "status" => $data->status
        ];
    }

    public function paginator($data)
    {
        $data->getCollection()->transform(function ($items, $key){
            return [
                "uid" => $items->id,
                "code" => $items->kode,
                "name" => $items->nama,
                "season" => $items->musim,
                "total_days" => $items->jml_hari,
                "start_date" => $items->tgl_mulai,
                "end_date" => $items->tgl_berakhir,
                "hotel" => $items->hotel,
                "airlines" => $items->maskapai
            ];
        });

        return $data;
    }
}