<?php

namespace App\Exports;

use App\Models\Spj;
use Maatwebsite\Excel\Concerns\FromCollection;

class SpjExporter implements FromCollection
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return Spj::with('satker')
            ->get()
            ->map(function ($record) {
                return [
                    'ID' => $record->id,
                    'Satker' => "($record->satker_id) " . "{$record->getRelation('satker')->nama_satker}",
                    'Judul SPJ' => $record->judul_spj,
                    'Deskripsi SPJ' => $record->deskripsi,
                    'Status' => $record->status,
                    'Lampiran' => config('app.app_url') .'/'. $record->attachment,
                    'Tanggal SPJ' => $record->tanggal_spj,
                ];
            });
    }

    public function headings()
    {
        return [
            'ID',
            'Satker',
            'Judul ESPEJE',
            'Deskripsi ESPEJE',
            'Status',
            'Lampiran',
        ];
    }
}
