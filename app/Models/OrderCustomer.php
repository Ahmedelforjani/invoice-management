<?php

namespace App\Models;

use App\Enums\InvoiceStatus;
use App\Observers\OrderCustomerObserver;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

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
}
