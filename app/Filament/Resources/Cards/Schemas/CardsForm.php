<?php

namespace App\Filament\Resources\Cards\Schemas;

use App\Enums\CardStatus;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Schemas\Schema;


class CardsForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->label('الاسم')
                    ->required(),

                TextInput::make('balance')
                    ->label('الرصيد')
                    ->numeric()
                    ->required()
                    ->default(0)
                    ->suffix('$'),

                TextInput::make('dues_amount')
                    ->label('المبلغ المستحق')
                    ->numeric()
                    ->required()
                    ->default(0)
                    ->suffix('$'),

//                Select::make('status')
//                    ->label('الحالة')
//                    ->default(CardStatus::UNPAID)
//                    ->options(CardStatus::class)
//                    ->required(),
            ]);
    }
}
