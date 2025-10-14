<?php

namespace App\Models;

use App\Enums\InvoiceStatus;
use App\Observers\InvoiceObserver;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

#[ObservedBy([InvoiceObserver::class])]
class Invoice extends Model
{
    use HasFactory;

    protected $casts = [
        'issue_date' => 'date',
        'paid_at' => 'date',
        'status' => InvoiceStatus::class
    ];

    protected $fillable = [
        'customer_id',
        'order_id',
        'subtotal_amount',
        'total_amount',
        'total_cost',
        'issue_date',
    ];

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function initialPayment(): HasOne
    {
        return $this->hasOne(Payment::class);
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
        $this->status = $this->paid_amount >= $this->total_amount ? InvoiceStatus::PAID : InvoiceStatus::ISSUED;
        $this->save();
    }

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }

    public function updatePaidAt(): void
    {
        $this->paid_at = $this->status == InvoiceStatus::PAID ? now() : null;
        $this->saveQuietly();
    }

    #[scope]
    public function notCancelled(Builder $query): Builder
    {
        return $query->whereNot('status', InvoiceStatus::CANCELLED);
    }

    #[scope]
    public function onlyPaid(Builder $query): Builder
    {
        return $query->whereStatus(InvoiceStatus::PAID);
    }

    #[scope]
    public function onlyIssued(Builder $query): Builder
    {
        return $query->whereStatus(InvoiceStatus::ISSUED);
    }

    #[Scope]
    public function withRemaining(Builder $query): Builder
    {
        return $query->addSelect(['remaining' => Invoice::selectRaw('total_amount - paid_amount')]);
    }
}
