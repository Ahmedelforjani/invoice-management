<?php

namespace App\Filament\Resources\Orders\Pages;

use App\Filament\Resources\Orders\OrdersResource;
use Filament\Resources\Pages\CreateRecord;

class CreateOrders extends CreateRecord
{
    protected static string $resource = OrdersResource::class;

    protected function afterCreate(): void
    {
        foreach ($this->record->customers as $customer) {
            $customer->createInvoice();
        }
    }
}
