<?php

namespace App\Transformers;

use Illuminate\Support\Facades\Log;

class PemesananTransformer
{
    public function transform($data){
        Log::info($data);

        return $data;
    }

    public function paginator($data){
        $data->getCollection()->transform(function ($item, $key){
            Log::info($item);
            return $item;
        });
        return $data;
    }
}