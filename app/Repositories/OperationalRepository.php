<?php

namespace App\Repositories;

use App\Interfaces\OperationalInt;
use App\Models\Paket;

class OperationalRepository implements OperationalInt
{
    private $paket;

    public function __construct(Paket $paket)
    {
        $this->paket = $paket;
    }

    public function all($data)
    {
        return $this->paket->with('hotel', 'maskapai')
            ->paginate(10);
    }

    public function show($id)
    {
        return $this->paket->with('hotel', 'maskapai', 'tipe', 'photo')
            ->where('id', $id)->first();
    }

    public function listAvailable($data){
        return $this->paket->with('photo', 'tipe')
        ->where('status', '!=', ['canceled', 'done'])
        ->limit(10)->orderBy('created_at', "ASC")->get();
    }

    public function recent(){
        return $this->paket->with('hotel', 'maskapai', 'tipe', 'photo')
        ->where('status', '!=', ['canceled', 'done'])
        ->limit(5)->orderBy('created_at', 'desc')->get();
    }

    public function detailCode($code){
        return $this->paket->where('kode', $code)
        ->with('photo', 'maskapai', 'hotel')->first();
    }
}
