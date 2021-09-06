<?php

namespace App\Services;
use App\Models\Maskapai;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class MaskapaiService extends ResultService
{
    private $maskapai;

    public function __construct(Maskapai $maskapai)
    {
       $this->maskapai = $maskapai;
    }

    public function setModel($id) : MaskapaiService{
        $maskapai = Maskapai::findorFail($id);
        $this->maskapai = $maskapai;

        return $this;
    }

    public function store(array $data){
        DB::beginTransaction();
        try{
            $maskapai = $this->maskapai;
            $maskapai->kode_maskapai = $data['kode'];
            $maskapai->nama = $data['name'];
            $maskapai->save();
            DB::commit();
            return $this->setResult($maskapai)->setFail(false);
        } catch (\Exception $e){
            DB::rollBack();
            return $this->setMessage($e->getMessage())->setFail(true);
        }
    }

    public function update(array $data){
        DB::beginTransaction();
        try{
            $this->maskapai->nama = $data['name'];
            $this->maskapai->kode_maskapai = $data['kode'];
            $this->maskapai->save();

            DB::commit();
            return $this->setResult($this->maskapai)->setFail(false);
        } catch(\Exception $e){
            DB::rollback();
            return $this->setMessage($e->getMessage())->setFail(true);
        }
    }

    public function destroy(){
        DB::beginTransaction();
        try{
            $this->maskapai->delete();

            DB::commit();
            return $this->setMessage(trans('message.destroy-data'). " " . trans('message.success'))->setFail(false);
        } catch (\Exception $exception){
            DB::rollBack();
            return $this->setMessage($exception->getMessage())->setFail(true);
        }
    }


}
