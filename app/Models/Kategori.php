<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kategori extends Model
{
    protected $table = "kategoris";
    protected $guarded = [];
    public function spj()
    {
        return $this->hasMany(Spj::class, 'id', 'kode_kategori');
    }
}
