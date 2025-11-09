<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Artisan::command('villar:migrate', function () {
    $invoices = \App\Models\Invoice::where('order_id', '=', 0)
        ->with(['customer', 'items'])
        ->get();

    $this->info("Found {$invoices->count()} invoices without orders");

    // Disable OrderCustomer observer to prevent automatic invoice creation
    \App\Models\OrderCustomer::withoutEvents(function () use ($invoices) {
        foreach ($invoices as $invoice) {
            $this->info("Processing Invoice #{$invoice->id}");

            // Create Order
            $order = \App\Models\Order::create([
                'status' => \App\Enums\OrderStatus::DELIVERED,
                'notes' => "Migrated from Invoice #{$invoice->id}",
                'subtotal_amount' => $invoice->subtotal_amount,
                'total_amount' => $invoice->total_amount,
                'total_cost' => $invoice->total_cost,
                'order_date' => $invoice->issue_date ?? now(),
            ]);

            $this->info("  Created Order #{$order->id}");

            // Create OrderCustomer without triggering observer
            $orderCustomer = \App\Models\OrderCustomer::create([
                'order_id' => $order->id,
                'customer_id' => $invoice->customer_id,
                'subtotal_amount' => $invoice->subtotal_amount,
                'total_amount' => $invoice->total_amount,
                'total_cost' => $invoice->total_cost,
                'paid_amount' => $invoice->paid_amount,
            ]);

            $this->info("  Created OrderCustomer #{$orderCustomer->id}");

            // Create OrderItems from InvoiceItems
            foreach ($invoice->items as $invoiceItem) {
                \App\Models\OrderItem::create([
                    'order_customer_id' => $orderCustomer->id,
                    'product_name' => $invoiceItem->description ?? 'N/A',
                    'quantity' => $invoiceItem->quantity ?? 1,
                    'unit_price' => $invoiceItem->unit_price ?? 0,
                    'cost_price' => 0, // Invoice items don't have cost price, default to 0
                    'total' => $invoiceItem->total ?? 0,
                ]);
            }

            $this->info("  Created {$invoice->items->count()} OrderItems");

            // Link Invoice to Order
            $invoice->update(['order_id' => $order->id]);

            $this->info("  Linked Invoice #{$invoice->id} to Order #{$order->id}");
            $this->newLine();
        }
    });

    $this->info("Migration completed successfully!");
});
