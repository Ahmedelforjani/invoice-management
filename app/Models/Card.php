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

    public function duesPayments(): HasMany
    {
        return $this->hasMany(CardDuePayment::class);
    }

    protected static function boot(){
        parent::boot();

        static::saving(function (Card $card) {
            $card->status = $card->dues_amount <= 0
                ? CardStatus::PAID
                : ($card->dues_amount < $card->balance
                    ? CardStatus::PARTIALLY_PAID
                    : CardStatus::UNPAID);
        });
    }

    public function updateBalance(float $amount): void
    {
        $this->balance += $amount;
        $this->save();
    }

    public function maxAllowedPayment(?CardPayment $editingPayment = null): float
    {
        return $this->balance + ($editingPayment?->amount ?? 0);
    }
    public function maxAllowedDuePayment(?CardDuePayment $editingPayment = null): float
    {
        return $this->dues_amount + ($editingPayment?->amount ?? 0);
    }

    public function updateDueAmount(float $amount): void
    {
        $this->dues_amount += $amount;
        $this->status = $this->dues_amount <= 0 ? CardStatus::PAID : ($this->dues_amount < $this->balance ? CardStatus::PARTIALLY_PAID : CardStatus::UNPAID);
        $this->save();
    }

}
