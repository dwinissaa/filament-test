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
        return $this->belongsTo(Satker::class, 'satker_id', 'id');
    }
}