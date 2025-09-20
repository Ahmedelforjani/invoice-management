<?php

namespace App\Filament\Resources\Cards\Pages;

use App\Filament\Resources\Cards\CardsResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditCards extends EditRecord
{
    protected static string $resource = CardsResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }
}
