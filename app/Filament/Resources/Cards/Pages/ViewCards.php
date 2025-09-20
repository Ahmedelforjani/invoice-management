<?php

namespace App\Filament\Resources\Cards\Pages;

use App\Filament\Resources\Cards\CardsResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewCards extends ViewRecord
{
    protected static string $resource = CardsResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
