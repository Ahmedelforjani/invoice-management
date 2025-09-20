<?php

namespace App\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasLabel;
use Illuminate\Contracts\Support\Htmlable;

enum InvoiceStatus: string implements HasLabel, HasColor
{
    case ISSUED = 'issued';
    case PAID = 'paid';
    case CANCELLED = 'cancelled';


    public function getColor(): string|array|null
    {
        return match ($this) {
            self::ISSUED => 'primary',
            self::PAID => 'success',
            self::CANCELLED => 'danger',
        };
    }

    public function getLabel(): string|Htmlable|null
    {
        return match ($this) {
            self::ISSUED => 'صادرة',
            self::PAID => 'مدفوعة',
            self::CANCELLED => 'ملغية',
        };
    }
}
