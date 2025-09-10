<?php

namespace App\Models;

use App\Enums\InvoiceStatus;
use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Invoice extends Model
{
    use HasFactory;

    protected $casts = [
        'issue_date' => 'date',
        'status' => InvoiceStatus::class
    ];

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function initialPayment(): HasOne
    {
        return $this->hasOne(Payment::class);
    }

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(InvoiceItem::class);
    }

    public function purchaseItems(): HasMany
    {
        return $this->hasMany(InvoicePurchaseItem::class);
    }

    public function updatePaidAmount(): void
    {
        $this->paid_amount = $this->payments()->sum('amount');
        if ($this->paid_amount >= $this->total_amount) $this->status = InvoiceStatus::PAID;
        $this->save();
    }

    #[scope]
    public function notCancelled(Builder $query): Builder {
        return $query->whereNot('status', InvoiceStatus::CANCELLED);
    }

    #[Scope]
    public function withRemaining(Builder $query): Builder
    {
        return $query->addSelect(['remaining' => Invoice::selectRaw('total_amount - paid_amount')]);
    }
}
