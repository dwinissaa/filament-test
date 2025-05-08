<?php

namespace App\Filament\Resources\FrekuensiResource\Pages;

use App\Filament\Resources\FrekuensiResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditFrekuensi extends EditRecord
{
    protected static string $resource = FrekuensiResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
