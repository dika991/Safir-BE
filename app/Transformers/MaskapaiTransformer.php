<?php

namespace App\Transformers;

class MaskapaiTransformer
{
    public function transform($data) : array
    {
        return [
            'id' => $data->id,
            'kode_maskapai' => $data->kode_maskapai,
            'name' => $data->nama
        ];
    }

    public function paginator($data)
    {
        $data->getCollection()->transform(function ($items, $key){
           return [
               'id' => $items->id,
               'kode_maskapai' => $items->kode_maskapai,
               'name' => $items->nama
           ];
        });

        return $data;
    }
}
