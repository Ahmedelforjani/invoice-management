<?php

namespace App\Filament\Resources\Withdrawals\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class WithdrawalsForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Textarea::make('description')
                    ->label('وصف السحب')
                    ->required()
                    ->rows(3),

                TextInput::make('amount')
                    ->label('القيمة')
                    ->numeric()
                    ->suffix('د.ل')
                    ->required()
                    ->minValue(0.01),

                DateTimePicker::make('withdrawal_date')
                    ->label('تاريخ السحب')
                    ->default(now())
                    ->required(),
            ]);
    }
}
