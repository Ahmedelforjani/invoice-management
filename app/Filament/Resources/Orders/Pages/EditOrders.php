<?php

namespace App\Filament\Resources\Orders\Pages;

use App\Filament\Resources\Orders\OrdersResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditOrders extends EditRecord
{
    protected static string $resource = OrdersResource::class;

    protected function afterSave(): void
    {
        foreach ($this->record->customers as $customer) {
            $customer->updateInvoice();
        }
    }

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }
}
