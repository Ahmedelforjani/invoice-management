<?php

namespace App\Filament\Resources\Orders\Pages;

use App\Filament\Resources\Orders\OrdersResource;
use App\Services\InvoiceService;
use Filament\Resources\Pages\EditRecord;

class EditOrders extends EditRecord
{
    protected static string $resource = OrdersResource::class;

    protected function afterSave(): void
    {

        $invoiceService = app(InvoiceService::class);

        $this->record->refresh();
        $this->record->load('customers.items');

        foreach ($this->record->customers as $customer) {
            $invoiceService->updateInvoice($customer);
        }
    }
}
