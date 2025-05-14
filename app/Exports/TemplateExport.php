<?php

namespace App\Exports;

use App\Models\Indikator;
use App\Models\JenisKarakteristik;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class TemplateExport implements FromView
{
    protected $indikator;
    protected $waktu_mulai;
    protected $waktu_selesai;

    public function __construct($indikator_id, $waktu_mulai, $waktu_selesai)
    {
        $this->indikator = Indikator::find($indikator_id);
        $this->waktu_mulai = $waktu_mulai;
        $this->waktu_selesai = $waktu_selesai;
    }
    public function view(): View
    {
        return view('exports.template-import-datas', [
            'indikator' => $this->indikator,
            'waktu_mulai' => $this->waktu_mulai,
            'waktu_selesai' => $this->waktu_selesai,
            'jenis_karakteristik' => JenisKarakteristik::query()->where('karakteristik_id', '=', $this->indikator->karakteristik_id)->get(),
        ]);
    }
}
