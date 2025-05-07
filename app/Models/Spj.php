<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Spj extends Model
{
    //
    protected $table = "spjs";
    protected $guarded = [];
    public function satker()
    {
        return $this->belongsTo(Satker::class, 'kode_satker', 'kode_satker');
    }

    public function kategori(){
        return $this->belongsTo(Kategori::class,'kode_kategori','id');
    }
}