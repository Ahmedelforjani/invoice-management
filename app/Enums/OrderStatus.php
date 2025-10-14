<?php

namespace App\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasLabel;
use Illuminate\Contracts\Support\Htmlable;
enum OrderStatus: string implements HasColor, HasLabel
{
    case ORDERED = 'ordered';
    case SHIPPING = 'shipping';
    case PREPARING = 'preparing';
    case DELIVERING = 'delivering';
    case DELIVERED = 'delivered';

    public function getColor(): string|array|null
    {
        return match ($this) {
            self::ORDERED => 'gray',
            self::PREPARING => 'warning',
            self::SHIPPING => 'info',
            self::DELIVERING => 'primary',
            self::DELIVERED => 'success',
        };
    }

    public function getLabel(): string|Htmlable|null
    {
        return match ($this) {
            self::ORDERED => 'تم الطلب',
            self::PREPARING => 'قيد التجهيز',
            self::SHIPPING => 'قيد الشحن',
            self::DELIVERING => 'قيد التوصيل',
            self::DELIVERED => 'تم التوصيل',
        };
    }
}
