<?php

namespace App\Transformers;

class HotelTransformer
{
    public function transform($data): array
    {
        return [
            'id' => $data->id,
            'name' => $data->name,
            'address' => $data->alamat,
            'location' => $data->lokasi
        ];
    }

    public function paginator($data)
    {
        $data->getCollection()->transform(function ($items, $key){
            return [
                'id' => $items->id,
                'name' => $items->name,
                'address' => $items->alamat,
                'location' => $items->lokasi
            ];
        });

        return $data;
    }
}
