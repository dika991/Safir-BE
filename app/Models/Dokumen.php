<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Dokumen extends Model
{
    protected $table = "dokumens";

    protected $fillable = [
        "jenis",
        "url",
        "id_jemaah"
    ];
}
