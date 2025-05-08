<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Frekuensi extends Model
{
    use HasFactory;
    //
    protected $table = "frekuensis";
    protected $guarded = [];
    public function indikator()
    {
        return $this->hasMany(Indikator::class, "frekuensi_id", "id");
    }
}
