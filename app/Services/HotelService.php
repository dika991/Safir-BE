<?php

namespace App\Services;

use App\Models\Hotel;
use Illuminate\Support\Facades\DB;

class HotelService extends ResultService
{
    private $hotel;

    public function __construct(Hotel $hotel)
    {
        $this->hotel = $hotel;
    }

    public function setModel($id): HotelService
    {
        $hotel = Hotel::findOrFail($id);
        $this->hotel = $hotel;

        return $this;
    }

    public function store(array $data)
    {
        DB::beginTransaction();
        try {
            $hotel = $this->hotel;
            $hotel->name = $data['name'];
            $hotel->alamat = $data['address'];
            $hotel->lokasi = $data['location'];
            $hotel->save();

            DB::commit();
            return $this->setResult($hotel)->setFail(false);
        }catch(\Exception $e){
            return $this->setMessage($e-->$this->getMessage())->setFail(true);
        }
    }

    public function update(array $data){
        DB::beginTransaction();
        try{
            $this->hotel->name = $data['name'];
            $this->hotel->alamat = $data['address'];
            $this->hotel->lokasi = $data['location'];
            $this->hotel->update();

            DB::commit();
            return $this->setResult($this->hotel)->setFail(false);
        }catch(\Exception $e){
            return $this->setMessage($e->getMessage())->setFail(true);
        }
    }

    public function destroy(){
        DB::beginTransaction();
        try{
            $this->hotel->delete();

            DB::commit();
            $result = [
                "message" => trans('message.destroy-data') . " " . trans('message.success')
            ];
            return $this->setResult($result)->setFail(false);
        }catch (\Exception $e){
            return $this->setMessage($e->getMessage())->setFail(true);
        }
    }
}
