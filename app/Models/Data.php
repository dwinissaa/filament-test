<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Data extends Model
{
    //
    protected $table = "datas";
    protected $guarded = ['kategori_id'];

    // protected $keyType = 'string';
    // public $incrementing = false;

    // public function getKeyName()
    // {
    //     return 'indikator_id,jenis_karakteristik_id';
    // }

    // public function getKey()
    // {
    //     // Custom key generator (dipakai Filament untuk recordKey, dst)
    //     return $this->indikator_id . '-' . $this->jenis_karakteristik_id;
    // }

    // protected $primaryKey = 'fake_key'; // atau string apapun, asal bukan kolom di database

    public function indikator()
    {
        return $this->belongsTo(Indikator::class, "indikator_id", "id");
    }

    public function jenis_karakteristik()
    {
        return $this->belongsTo(JenisKarakteristik::class, "jenis_karakteristik_id", "id");
    }
}
