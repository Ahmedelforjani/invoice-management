<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CardDuePayment extends Model
{
    use HasFactory;

    protected $casts = [
        'amount' => 'decimal:2',
    ];

    public function card(): BelongsTo
    {
        return $this->belongsTo(Card::class);
    }
}
