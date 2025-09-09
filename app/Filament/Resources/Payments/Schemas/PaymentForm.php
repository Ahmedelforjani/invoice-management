<?php

namespace App\Filament\Resources\Payments\Schemas;

use Filament\Actions\Action;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Illuminate\Database\Eloquent\Model;

class PaymentForm
{
    public static function configure(Schema $schema, ?Model $invoice = null): Schema
    {
        $remainingAmount = $invoice?->total_amount - $invoice?->payments?->sum('amount');

        return $schema
            ->components([
                Section::make("دفعة للفاتورة #{$invoice?->id}")
                    ->schema([
                        TextInput::make('amount')
                            ->label('القيمة')
                            ->helperText("المبلغ المتبقى هو {$remainingAmount} د.ل")
                            ->numeric()
                            ->suffix('د.ل')
                            ->required()
                            ->maxValue($remainingAmount)
                            ->minValue(0.1)
                            ->prefixAction(
                                Action::make('fillRemaining')
                                    ->icon('heroicon-m-cursor-arrow-ripple')
                                    ->action(fn (Set $set) =>  $set('amount', $remainingAmount))
                            )
                    ])
                    ->columnSpanFull(),
            ]);
    }
}
