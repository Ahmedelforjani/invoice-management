<?php

namespace App\Services;

use App\Models\Invoice;
use App\Models\OrderCustomer;

class InvoiceService
{
    /**
     * Create an invoice for the given OrderCustomer.
     *
     * @param OrderCustomer $orderCustomer
     * @return void
     */
    public function createInvoice(OrderCustomer $orderCustomer): void
    {
        $orderCustomer->loadMissing('items');

        $invoice = Invoice::create([
            'order_id' => $orderCustomer->order_id,
            'customer_id' => $orderCustomer->customer_id,
            'subtotal_amount' => $orderCustomer->subtotal_amount,
            'total_amount' => $orderCustomer->total_amount,
            'total_cost' => $orderCustomer->total_cost,
            'discount' => $orderCustomer->discount_amount,
            'issue_date' => now(),
        ]);

        foreach ($orderCustomer->items as $item) {
            $invoice->items()->create([
                'description' => $item->product_name,
                'quantity' => $item->quantity,
                'unit_price' => $item->unit_price,
                'total' => $item->total,
            ]);
        }

        if ($orderCustomer->paid_amount > 0) {
            $invoice->payments()->create([
                'amount' => $orderCustomer->paid_amount,
            ]);
        }
    }

    /**
     * Update the invoice for the given OrderCustomer.
     *
     * @param OrderCustomer $orderCustomer
     * @return void
     */
    public function updateInvoice(OrderCustomer $orderCustomer): void
    {
        $orderCustomer->loadMissing('items');

        $invoice = Invoice::where('order_id', $orderCustomer->order_id)
            ->where('customer_id', $orderCustomer->customer_id)
            ->first();

        if (! $invoice) {
            // If no invoice exists, create a new one
            $this->createInvoice($orderCustomer);
            return;
        }

        $invoice->update([
            'subtotal_amount' => $orderCustomer->subtotal_amount,
            'total_amount' => $orderCustomer->total_amount,
            'total_cost' => $orderCustomer->total_cost,
            'discount' => $orderCustomer->discount_amount,
        ]);

        $invoice->items()->delete();
        foreach ($orderCustomer->items as $item) {
            $invoice->items()->create([
                'description' => $item->product_name,
                'quantity' => $item->quantity,
                'unit_price' => $item->unit_price,
                'total' => $item->total,
            ]);
        }

        if ($orderCustomer->paid_amount > 0) {
            $invoice->payments()->updateOrCreate(
                ['invoice_id' => $invoice->id],
                ['amount' => $orderCustomer->paid_amount]
            );
        }
    }
}
