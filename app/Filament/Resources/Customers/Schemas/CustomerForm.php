<?php

namespace App\Filament\Resources\Customers\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class CustomerForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->label('الاسم')
                    ->required()
                    ->maxLength(255)
                    ->placeholder('ادخل اسم الزبون'),

                TextInput::make('phone')
                    ->label('الهاتف')
                    ->required()
                    ->tel()
                    ->maxLength(255)
                    ->placeholder('ادخل رقم الهاتف'),

                TextInput::make('address')
                    ->label('العنوان')
                    ->maxLength(65535)
                    ->placeholder('ادخل العنوان')
                    ->columnSpanFull(),
            ]);
    }
}
