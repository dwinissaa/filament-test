<?php

namespace App\Http\Controllers;

use App\Imports\DatasImport;
use Illuminate\Http\Request;
use App\Exports\TemplateExport;
use Maatwebsite\Excel\Facades\Excel;

class DatasController extends Controller
{

    public function download_template($indikator_id, $waktu_mulai, $waktu_selesai)
    {
        return Excel::download(new TemplateExport($indikator_id, $waktu_mulai, $waktu_selesai), 'impor-datas-template.xlsx');
    }


}
