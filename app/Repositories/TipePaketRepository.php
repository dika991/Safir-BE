<?php
namespace App\Repositories;

use App\Interfaces\TipeInt;
use App\Models\Tipe;

class TipePaketRepository implements TipeInt
{
    private $tipe;

    public function __construct(Tipe $tipe)
    {
        $this->tipe = $tipe;
    }

    public function all($data, $id)
    {
        return $this->tipe->where('id_paket', $id)->get();
    }

    public function show($id)
    {
        return $this->tipe->where('id', $id)->first();
    }
}
