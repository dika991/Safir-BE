<?php

namespace App\Repositories;

use App\Interfaces\MaskapaiInt;
use App\Models\Maskapai;

class MaskapaiRepository implements MaskapaiInt
{
    private $maskapai;

    public function __construct(Maskapai $maskapai)
    {
        $this->maskapai = $maskapai;
    }

    public function all($data)
    {
        $maskapai = $this->maskapai->orderBy('id', 'desc');
        if (array_key_exists('q', $data)){
            $maskapai->where('nama', 'LIKE', '%' . $data['q'] . '%');
        }
        return $maskapai->paginate(10);
    }

    public function show($id)
    {
        return $this->maskapai->where('id', $id)->first();
    }

    public function allMaskapai($data){
        $maskapai = $this->maskapai;
        if(array_key_exists('q', $data)){
            $maskapai->where('nama', 'LIKE', '%' . $data['q'] . '%');
        }
        return $maskapai->get();
    }
}
