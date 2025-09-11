<?php

namespace App\Observers;

use App\Models\Invoice;

class InvoiceObserver
{
    public function created(Invoice $invoice): void
    {
        $invoice->updatePaidAt();
    }

    public function updated(Invoice $invoice): void
    {
        $invoice->updatePaidAt();
    }
}
