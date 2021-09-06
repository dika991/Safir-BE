<?php

namespace App\Transformers;

class TipeTransformer {
    public function transform($data) : array
    {
        return [
            "id" => $data['id'],
            "packages_id" => $data['id_paket'],
            "name"=> $data['nama'],
            "prices" => $data['harga'],
            "quota" => $data['kuota']
        ];
    }

    public function allData($data)
    {
        $data->transform(function ($items, $key)
        {
            return [
                "id" => $items['id'],
                "packages_id" => $items['id_paket'],
                "name" => $items['nama'],
                "prices" => $items['harga'],
                "quota" => $items['kuota']
            ];
        });
        return $data;
    }
}
