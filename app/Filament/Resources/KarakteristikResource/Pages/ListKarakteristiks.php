<?php

namespace App\Filament\Resources\KarakteristikResource\Pages;

use App\Filament\Resources\KarakteristikResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListKarakteristiks extends ListRecords
{
    protected static string $resource = KarakteristikResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
