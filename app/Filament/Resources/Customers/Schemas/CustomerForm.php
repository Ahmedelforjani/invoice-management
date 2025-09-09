<?php

namespace App\Filament\Resources\Customers\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Fieldset;
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

                Fieldset::make('إعدادات')
                    ->relationship('settings')
                    ->schema([
                        Toggle::make('show_total_remaining_in_invoice')
                            ->label('عرض المتبقي في الفاتورة؟')
                            ->default(false),
                    ])->columns(3)
                ->columnSpanFull(),

            ]);
    }
}
