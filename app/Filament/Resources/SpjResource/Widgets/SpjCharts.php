<?php

namespace App\Filament\Resources\SpjResource\Widgets;

use App\Models\Spj;
use Filament\Widgets\ChartWidget;

class SpjCharts extends ChartWidget
{
    protected static ?string $heading = 'Chart';

    protected function getData(): array
    {
        $spj_data = Spj::selectRaw('DATE_FORMAT(tanggal_spj, "%Y-%m-%d") as date, COUNT(id) as total')
            ->groupBy('date')
            ->get();
            
        $labels = $spj_data->pluck('date')->toArray();
        $total = $spj_data->pluck('total')->toArray();

        return [
            'datasets' => [
                [
                    'label' => "Jumlah SPJ per Hari",
                    'data' => $total,
                ],
            ],
            'labels' => $labels
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
