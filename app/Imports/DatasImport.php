<?php

namespace App\Imports;

use App\Models\Data;
use App\Models\Indikator;
use App\Models\JenisKarakteristik;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class DatasImport implements ToCollection, WithHeadingRow, WithValidation
{
    use Importable;

    protected $indikator;
    protected $currentYear;
    public function __construct($indikator)
    {
        $this->indikator = $indikator;
    }

    public function collection(Collection $rows)
    {
        foreach ($rows as $row) {
            Data::create([
                'indikator_id' => $row['indikator_id'],
                'waktu' => $row['waktu'],
                'jenis_karakteristik_id' => $row['jenis_karakteristik_id'],
                'data' => $row['data'],
            ]);
        }
    }

    public function headingRow(): int
    {
        return 1;
    }

    public function rules(): array
    {
        $this->currentYear = now()->year;

        $dataType = match ($this->indikator->tipe_indikator) {
            'string' => 'string',
            'integer' => 'numeric',
            'float' => 'numeric',
            default => 'string', // fallback kalau tipe tidak diketahui
        };

        return [
            'indikator_id' => ['required', 'exists:indikators,id'],      // kolom pertama harus string
            'waktu' => ['required', 'numeric', 'min:2000', 'max:' . $this->currentYear],        // kolom kedua harus tanggal
            'jenis_karakteristik_id' => ['required', 'numeric', 'exists:jenis_karakteristiks,id'],     // kolom ketiga boleh kosong atau angka
            'data' => [$dataType]
        ];
    }

    public function customValidationMessages()
    {
        return [
            'indikator_id.required' => 'ID Indikator tidak sesuai',
            'waktu.min:2000' => 'Tahun harus antara 2000 s.d. ' . $this->currentYear,
            'waktu.max:' . $this->currentYear => 'Tahun harus antara 2000 s.d. ' . $this->currentYear,
            'jenis_karakteristik_id.exists:jenis_karakteristiks,id' => 'Jenis Karakteristik tidak sesuai',
            'data' => 'Tipe Data tidak sesuai',
        ];
    }
}
