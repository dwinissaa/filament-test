<?php

namespace App\Filament\Exports;

use App\Models\Spj;
use Illuminate\Support\Facades\Log;
use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Models\Export;
use App\Services\SpjExportService;

class SpjExporter extends Exporter
{
    protected static ?string $model = Spj::class;

    public static function getColumns(): array
    {
        return [
            ExportColumn::make('id')
                ->label('ID'),
            ExportColumn::make('satker_id')
                ->formatStateUsing(fn($state,$record) => "($state) " . "{$record->satker->nama_satker}" ?? 'N/A'),
            ExportColumn::make('judul_spj'),
            ExportColumn::make('deskripsi'),
            ExportColumn::make('status'),
            ExportColumn::make('attachment'),
            ExportColumn::make('created_at'),
            ExportColumn::make('updated_at'),
        ];
    }

    public static function getCompletedNotificationBody(Export $export): string
    {
        $body = 'Your spj export has completed and ' . number_format($export->successful_rows) . ' ' . str('row')->plural($export->successful_rows) . ' exported.';

        if ($failedRowsCount = $export->getFailedRowsCount()) {
            $body .= ' ' . number_format($failedRowsCount) . ' ' . str('row')->plural($failedRowsCount) . ' failed to export.';
        }

        return $body;
    }

    public function query()
    {
        Log::info('Running Export Query...');
        return Spj::query();
    }
}