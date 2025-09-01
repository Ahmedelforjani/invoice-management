<?php

namespace App\Filament\Resources\Customers\Tables;

use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class CustomersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                    ->label('#')
                    ->sortable()
                    ->searchable(),

                TextColumn::make('name')
                    ->label('الاسم')
                    ->sortable()
                    ->searchable(),

                TextColumn::make('phone')
                    ->label('الهاتف')
                    ->copyable()
                    ->sortable()
                    ->searchable(),

                TextColumn::make('address')
                    ->label('العنوان')
                    ->limit(50)
                    ->tooltip(function (TextColumn $column): ?string {
                        $state = $column->getState();
                        if (strlen($state) <= 50) {
                            return null;
                        }
                        return $state;
                    }),

                TextColumn::make('invoices_count')
                    ->label('الفواتير')
                    ->counts('invoices')
                    ->sortable(),

                TextColumn::make('created_at')
                    ->label('تاريخ الاضافة')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->defaultSort('created_at', 'desc');
    }
}
