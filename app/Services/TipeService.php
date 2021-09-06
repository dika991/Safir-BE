<?php

namespace App\Services;

use App\Models\Tipe;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class TipeService extends ResultService
{
    private $tipe;

    /**
     * Konstruktor
     *
     * @param Tipe $tipe
     */
    public function __construct(Tipe $tipe)
    {
        $this->tipe = $tipe;
    }

    /**
     * Set Model
     *
     * @param [type] $id
     * @return TipeService
     */
    public function setModel($id) : TipeService
    {
        $tipe = Tipe::find($id);

        $this->tipe = $tipe;

        return $this;
    }

    /**
     * @param array $data
     * @return TipeService
     */
    public function store(array $data)
    {
        DB::beginTransaction();
        try{
            $tipe = $this->tipe;
            $tipe->nama = $data['name'];
            $tipe->harga = $data['prices'];
            $tipe->kuota = $data['quota'];
            $tipe->id_paket = $data['package_id'];
            $tipe->save();
            DB::commit();
            return $this->setResult($tipe)->setFail(false);
        }catch(\Exception $e){
            DB::rollBack();
            return $this->setMessage($e->getMessage())->setFail(true);
        }
    }

    /**
     * @param array $data
     * @return TipeService
     */
    public function update(array $data){
        DB::beginTransaction();
        try{
            $this->tipe->nama = $data['name'];
            $this->tipe->harga = $data['prices'];
            $this->tipe->kuota = $data['quota'];
            $this->tipe->id_paket = $data['package_id'];

            $this->tipe->save();
            DB::commit();
            return $this->setResult($this->tipe)->setFail(false);
        }catch(\Exception $e)
        {
            DB::rollBack();
            return $this->setMessage($e->getMessage())->setFail(true);
        }
    }

    /**
     * @return TipeService
     */
    public function destroy()
    {
        DB::beginTransaction();
        try{
            if(!$this->tipe){
                return $this->setMessage(trans('message.not-found'))->setFail(true);
            }
            $this->tipe->delete();
            DB::commit();
            return $this->setMessage(trans('message.destroy-data') . " " . trans('message.success'))->setFail(false);
        }catch(\Exception $e){
            DB::rollBack();
            return $this->setMessage($e->getMessage())->setFail(true);
        }
    }
}
