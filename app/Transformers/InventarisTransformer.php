<?php

namespace App\Transformers;

class InventarisTransformer
{
    public function transform($data){
        return $data;
    }

    public function paginator($data){
        $data->getCollection()->transform(function ($items, $key){
            return $items;
        });

        return $data;
    }
}