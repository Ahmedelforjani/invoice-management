<?php

namespace App\Models;

use App\Enums\InvoiceStatus;
use App\Enums\OrderStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    protected $fillable = [
        'status',
        'notes',
        'subtotal_amount',
        'total_amount',
        'total_cost',
        'order_date',
    ];

    protected $casts = [
        'order_date' => 'date',
        'status' => OrderStatus::class
    ];

    protected static function booted()
    {
        static::deleting(function (Order $order) {
            $order->cancelInvoice();
        });
    }

    public function customers(): HasMany
    {
        return $this->hasMany(OrderCustomer::class);
    }

    public function cancelInvoice(): void
    {
        $invoices = Invoice::where('order_id', $this->id)->get();

        foreach ($invoices as $invoice) {
            $invoice->update(['status' => InvoiceStatus::CANCELLED]);
        }
    }
}
