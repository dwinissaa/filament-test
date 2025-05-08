<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Data extends Model
{
    //
    protected $table = "datas";
    protected $guarded = [];
    public function indikator()
    {
        return $this->belongsTo(Indikator::class, "indikator_id", "id");
    }

    public function jenis_karakteristik()
    {
        return $this->belongsTo(JenisKarakteristik::class, "jenis_karakteristik_id", "id");
    }
}
