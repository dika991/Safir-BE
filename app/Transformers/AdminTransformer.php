<?php

namespace App\Transformers;

class AdminTransformer
{
    public function transform($data){
        return $data;
    }

    public function paginator($data)
    {
        $data->getCollection()->transform(function ($items, $key){
            return $items;
        });
        return $data;
    }
}