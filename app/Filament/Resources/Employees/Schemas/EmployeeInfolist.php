<?php

namespace App\Filament\Resources\Employees\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class EmployeeInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make()
                    ->schema([
                        TextEntry::make('name')
                            ->label('الاسم'),
                        TextEntry::make('phone')
                            ->label('الهاتف')
                            ->copyable(),
                    ])
                    ->columns()
                    ->columnSpanFull()
            ]);
    }
}
