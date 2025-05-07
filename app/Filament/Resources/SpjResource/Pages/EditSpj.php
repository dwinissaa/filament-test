<?php

namespace App\Filament\Resources\SpjResource\Pages;

use App\Filament\Resources\SpjResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditSpj extends EditRecord
{
    protected static string $resource = SpjResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
