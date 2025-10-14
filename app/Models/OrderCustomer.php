<?php

namespace App\Models;

use App\Enums\InvoiceStatus;
use App\Observers\OrderCustomerObserver;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[ObservedBy(OrderCustomerObserver::class)]
class OrderCustomer extends Model
{
    protected $fillable = [
        'order_id',
        'customer_id',
        'subtotal_amount',
        'total_amount',
        'total_cost',
    ];

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function customer(): belongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }
    public function createInvoice(): void
    {
        $this->loadMissing('items');

        $invoice = Invoice::create([
            'order_id' => $this->order_id,
            'customer_id' => $this->customer_id,
            'subtotal_amount' => $this->subtotal_amount,
            'total_amount' => $this->total_amount,
            'total_cost' => $this->total_cost,
            'discount' => $this->discount_amount,
            'issue_date' => now(),
        ]);

        foreach ($this->items as $item) {
            $invoice->items()->create([
                'description' => $item->product_name,
                'quantity' => $item->quantity,
                'unit_price' => $item->unit_price,
                'total' => $item->total,
            ]);
        }

        if ($this->paid_amount > 0) {
            $invoice->payments()->create([
                'amount' => $this->paid_amount,
            ]);
        }
    }
    public function updateInvoice(): void
    {
        $this->loadMissing('items');

        $invoice = Invoice::where('order_id', $this->order_id)
            ->where('customer_id', $this->customer_id)
            ->first();

        if (! $invoice) {
            $this->createInvoice();
            return;
        }

        $invoice->update([
            'subtotal_amount' => $this->subtotal_amount,
            'total_amount' => $this->total_amount,
            'total_cost' => $this->total_cost,
            'discount' => $this->discount_amount,
        ]);

        $invoice->items()->delete();
        foreach ($this->items as $item) {
            $invoice->items()->create([
                'description' => $item->product_name,
                'quantity' => $item->quantity,
                'unit_price' => $item->unit_price,
                'total' => $item->total,
            ]);
        }

        if ($this->paid_amount > 0) {
            $invoice->payments()->updateOrCreate(
                ['invoice_id' => $invoice->id],
                ['amount' => $this->paid_amount]
            );
        }
    }
}
