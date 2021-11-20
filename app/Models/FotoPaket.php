<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FotoPaket extends Model
{
    use HasFactory;

    protected $appends = ['link'];

    public function getLinkAttribute(){
        return config('app.url')."storage/".str_replace('public/','',$this->path);
    }
}
