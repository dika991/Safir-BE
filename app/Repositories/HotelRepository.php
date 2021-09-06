<?php

namespace App\Repositories;

use App\Interfaces\HotelInt;
use App\Models\Hotel;

class HotelRepository implements HotelInt
{
    private $hotel;

    public function __construct(Hotel $hotel)
    {
        $this->hotel = $hotel;
    }

    public function list($data){
        $hotel = $this->hotel;
        if(array_key_exists('q', $data)){
            $hotel->where('name', 'LIKE', '%' . $data['q'] . '%');
        }
        return $hotel->get();
    }

    public function show($id)
    {
        return $this->hotel->where('id', $id)->first();
    }

    public function all($data)
    {
        $hotel = $this->hotel->orderBy('id', 'desc');
        if(array_key_exists('q', $data)){
            $hotel->where('name', 'LIKE', '%' . $data['q'] . '%');
        }
        return $hotel->paginate(10);
    }
}
