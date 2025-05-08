<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Karakteristik extends Model
{
    use HasFactory;
    //
    protected $table = "karakteristiks";
    protected $guarded = [];

    public function jenis_karakteristik(){
        return $this->hasMany(JenisKarakteristik::class,"karakteristik_id","id");
    }

    public function indikator(){
        return $this->hasMany(Indikator::class,"karakteristik_id","id");
    }
}
