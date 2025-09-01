<?php

namespace App\Filament\Resources\Payments\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Database\Eloquent\Model;

class PaymentForm
{
    public static function configure(Schema $schema, ?Model $invoice = null): Schema
    {
        $remainingAmount = $invoice?->total - $invoice?->payments?->sum('amount');

        return $schema
            ->components([
                Section::make("دفعة للفاتورة #{$invoice?->id}")
                    ->schema([
                        TextInput::make('amount')
                            ->label('القيمة')
                            ->helperText("المبلغ المتبقى: {$remainingAmount}")
                            ->numeric()
                            ->suffix('د.ل')
                            ->required()
                            ->maxValue($remainingAmount)
                            ->minValue(0.01),
                    ])
                    ->columnSpanFull(),
            ]);
    }
}
