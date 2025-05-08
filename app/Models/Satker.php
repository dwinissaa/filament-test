<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Satker extends Model
{
    use HasFactory;
    protected $table = "satkers";
    protected $guarded = [];
    protected $primaryKey = 'id';
    public $incrementing = false;
    protected $keyType = 'string';

    public function spj()
    {
        return $this->hasMany(Spj::class,'satker_id','id');
    }
}
