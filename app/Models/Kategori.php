<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kategori extends Model
{
    protected $table = "kategoris";
    protected $guarded = [];
    public function indikator()
    {
        return $this->hasMany(Indikator::class, 'kategori_id', 'id');
    }
}
