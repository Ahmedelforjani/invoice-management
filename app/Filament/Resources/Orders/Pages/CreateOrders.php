<?php

namespace App\Filament\Resources\Orders\Pages;

use App\Filament\Resources\Orders\OrdersResource;
use App\Services\InvoiceService;
use Filament\Resources\Pages\CreateRecord;

class CreateOrders extends CreateRecord
{
    protected static string $resource = OrdersResource::class;

    protected function afterCreate(): void
    {
        $invoiceService = app(InvoiceService::class);

        $this->record->load('customers.items');

        foreach ($this->record->customers as $customer) {
            $invoiceService->createInvoice($customer);
        }
    }
}
