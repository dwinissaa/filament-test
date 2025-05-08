<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JenisKarakteristik extends Model
{
    //

    protected $table = "jenis_karakteristiks";

    protected $guarded = [];

    public function karakteristik()
    {
        return $this->belongsTo(Karakteristik::class, "karakteristik_id", "id");
    }

    public function data(){
        return $this->hasMany(Data::class,"jenis_karekteristik_id","id");
    }

}
