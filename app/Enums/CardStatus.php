<?php

namespace App\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasLabel;
use Illuminate\Contracts\Support\Htmlable;

enum CardStatus: string implements HasColor, HasLabel
{
    case UNPAID = 'unpaid';
    case PAID = 'paid';

    public function getLabel(): string|Htmlable|null
    {
        return match ($this) {
            self::UNPAID => 'غير مدفوعة',
            self::PAID => 'مدفوعة',
        };
    }
    public function getColor(): string
    {
        return match ($this) {
            self::PAID => 'success',
            self::UNPAID => 'danger',
        };
    }
}
