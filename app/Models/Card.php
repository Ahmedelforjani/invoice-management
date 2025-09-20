<?php

namespace App\Models;

use App\Enums\CardStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Card extends Model
{
    use HasFactory;

    protected $casts = [
        'status' => CardStatus::class,
        'balance' => 'decimal:2',
    ];

    public function paymentsHistory(): HasMany
    {
        return $this->hasMany(CardPayment::class);
    }

    public function updateBalance(float $amount): void
    {
        $this->balance += $amount;
        $this->status = $this->balance <= 0 ? CardStatus::PAID : CardStatus::UNPAID;
        $this->save();
    }

    public function maxAllowedPayment(?CardPayment $editingPayment = null): float
    {
        return $this->balance + ($editingPayment?->amount ?? 0);
    }
}
