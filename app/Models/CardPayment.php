<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CardPayment extends Model
{
    use HasFactory;

    protected $table = 'card_payment_history';

    public function card(): BelongsTo
    {
        return $this->belongsTo(Card::class);
    }
}
