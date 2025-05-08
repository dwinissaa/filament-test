<?php

namespace App\Filament\Resources\KarakteristikResource\Pages;

use App\Filament\Resources\KarakteristikResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditKarakteristik extends EditRecord
{
    protected static string $resource = KarakteristikResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
