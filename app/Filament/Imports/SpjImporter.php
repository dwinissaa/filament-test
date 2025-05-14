<?php

namespace App\Filament\Imports;

use App\Models\Spj;
use Filament\Actions\Imports\ImportColumn;
use Filament\Actions\Imports\Importer;
use Filament\Actions\Imports\Models\Import;

class SpjImporter extends Importer
{
    protected static ?string $model = Spj::class;

    public static function getColumns(): array
    {
        return [
            ImportColumn::make('satker_id')
                ->requiredMapping()
                ->rules(['required', 'max:255']),
            ImportColumn::make('judul_spj')
                ->requiredMapping()
                ->rules(['required', 'max:255']),
            ImportColumn::make('deskripsi')
                ->requiredMapping()
                ->rules(['required', 'max:255']),
            ImportColumn::make('status')
                ->requiredMapping()
                ->rules(['required', 'integer', 'between:1,5']),
            ImportColumn::make('tanggal_spj')
                ->label('Tanggal (YYY-MM-DD)')
                ->requiredMapping()
                ->rules(['required', 'date']), // hanya akan menerima format yang valid,
            ImportColumn::make('attachment')
                ->requiredMapping()
                ->rules(['required', 'max:255']),
        ];
    }

    public function resolveRecord(): ?Spj
    {
        // return Spj::firstOrNew([
        //     // Update existing records, matching them by `$this->data['column_name']`
        //     'email' => $this->data['email'],
        // ]);

        return new Spj();
    }

    public static function getCompletedNotificationBody(Import $import): string
    {
        $body = 'Your spj import has completed and ' . number_format($import->successful_rows) . ' ' . str('row')->plural($import->successful_rows) . ' imported.';

        if ($failedRowsCount = $import->getFailedRowsCount()) {
            $body .= ' ' . number_format($failedRowsCount) . ' ' . str('row')->plural($failedRowsCount) . ' failed to import.';
        }

        return $body;
    }
}
