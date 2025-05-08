<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Indikator extends Model
{
    //

    protected $table = "indikators";
    protected $guarded = [];

    public function satker()
    {
        return $this->belongsTo(Satker::class, "satker_id", "id");
    }

    public function karakteristik()
    {
        return $this->belongsTo(Karakteristik::class, "karakteristik_id", "id");
    }

    public function frekuensi()
    {
        return $this->belongsTo(Frekuensi::class, "frekuensi_id", "id");
    }

    public function data()
    {
        return $this->hasMany(Data::class, "indikator_id", "id");
    }


    public function kategori()
    {
        return $this->belongsTo(Kategori::class, "kategori_id", "id");
    }
}
