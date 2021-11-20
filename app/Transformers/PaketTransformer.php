<?php

namespace App\Transformers;

use Illuminate\Support\Facades\Log;

class PaketTransformer
{
    public function transform($data): array
    {
        return [
            "uid" => $data->id,
            "code" => $data->kode,
            "name" => $data->nama,
            "season" => $data->musim,
            "status" => $data->status,
            "total_days" => $data->jml_hari,
            "start_date" => $data->tgl_mulai,
            "end_date" => $data->tgl_berakhir,
            "hotel_id" => $data->id_hotel,
            "airlines_id" => $data->id_maskapai,
            "hotel" => $data->hotel,
            "airlines" => $data->maskapai,
            "types" => $data->tipe,
            "status" => $data->status,
            "photos" => $data->photo,
            "airlines" => $data->maskapai,
            "hotels" => $data->hotel,
            "desc" => "Paket ini merupakan paket yang ekonomis, fasilitas yang disediakan sangat terpenuhi meskipun dengan harga yang sangat terjangkau. Tour guide yang sangat ramah dan juga bimbingan yang diberikan akan sangat membantu jamaah yang akan berangkat umrah. Pada paket ini jamaah akan mendapatkan tujuan ke berbagai tempat seperti Makam Nabi Muhammad SAW, Goa Hiro, Dll."
        ];
    }

    public function paginator($data)
    {
        $data->getCollection()->transform(function ($items, $key) {
            return [
                "uid" => $items->id,
                "code" => $items->kode,
                "name" => $items->nama,
                "season" => $items->musim,
                "status" => $items->status,
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
