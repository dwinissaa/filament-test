<?php

namespace App\Services;

use App\Models\Spj;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class SpjExportService
{
    public static function generate(): string
    {
        $filename = 'exports/spj_export_' . now()->format('Ymd_His') . '.xlsx';

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $data = Spj::with('satker')->get();

        // Header
        $sheet->fromArray([
            ['ID', 'Satker', 'Judul', 'Deskripsi', 'Status', 'Tanggal']
        ], null, 'A1');

        // Data
        $rows = [];
        foreach ($data as $row) {
            $rows[] = [
                $row->id,
                "({$row->satker_id}) " . ($row->satker->nama_satker ?? 'N/A'),
                $row->judul_spj,
                $row->deskripsi,
                $row->status,
                $row->created_at,
            ];
        }

        $sheet->fromArray($rows, null, 'A2');

        $writer = new Xlsx($spreadsheet);
        $tempPath = storage_path('app/' . $filename);
        $writer->save($tempPath);

        return $filename; // hanya path relatif ke storage/app/
    }
}
