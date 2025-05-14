<?php

namespace App\Http\Controllers;

use App\Exports\SpjExporter;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class SpjController extends Controller
{
    public function export(){
        return Excel::download(new SpjExporter, 'ini_spj.xlsx');
    }

    public function import(){
        
    }
}