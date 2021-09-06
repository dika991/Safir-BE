<?php

namespace App\Services;

use App\Models\Paket;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class OperationalService extends ResultService
{
    private $paket;

    public function __construct(Paket $paket)
    {
        $this->path = 'storage/paket/';
        $this->paket = $paket;
    }

    public function setModel($id): OperationalService
    {
        $paket = Paket::find($id);
        $this->paket = $paket;

        return $this;
    }

    public function store(array $data)
    {
        $start = Carbon::parse($data['start_date']);
        $end = Carbon::parse($data['end_date']);
        $diff = $start->diffInDays($end);
        DB::beginTransaction();
        try {
            $paket = $this->paket;
            $paket->kode = $data['code'];
            $paket->nama = $data['name'];
            $paket->musim = $data['season'];
            $paket->jml_hari = $diff;
            $paket->tgl_mulai = $start;
            $paket->tgl_berakhir = $end;
            $paket->id_hotel = $data['hotel_id'];
            $paket->id_maskapai = $data['airlines_id'];
            $paket->save();

            DB::commit();
            return $this->setResult($paket)->setFail(false);

        } catch (\Exception $e) {
            Log::info($e->getMessage());
            DB::rollBack();
            return $this->setMessage($e->getMessage())->setFail(true);
        }

    }

    public function update(array $data)
    {
        $start = Carbon::parse($data['start_date']);
        $end = Carbon::parse($data['end_date']);
        $diff = $start->diffInDays($end);
        DB::beginTransaction();
        try {
            $paket = $this->paket;
            $paket->nama = $data['name'];
            $paket->musim = $data['season'];
            $paket->jml_hari = $diff;
            $paket->tgl_mulai = $start;
            $paket->tgl_berakhir = $end;
            $paket->id_hotel = $data['hotel_id'];
            $paket->id_maskapai = $data['airlines_id'];
            $paket->save();

            DB::commit();
            return $this->setResult($paket)->setFail(false);
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->setMessage($e->getMessage())->setFail(true);
        }
    }

    public function destroy()
    {
        DB::beginTransaction();
        try {
            if (!$this->paket) {
                return $this->setMessage(trans('message.not-found'))->setFail(true);
            }
            $this->paket->delete();
            DB::commit();
            return $this->setMessage(trans('message.destroy-data') . " " . trans('message.success'))->setFail(false);
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->setMessage($e->getMessage())->setFail(true);
        }
    }
}
