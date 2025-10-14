<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderItem extends Model
{
    protected $fillable = ['order_customer_id', 'product_name', 'quantity', 'unit_price','unit_cost', 'total'];

    public function orderCustomer(): BelongsTo
    {
        return $this->belongsTo(OrderCustomer::class);
    }
}
