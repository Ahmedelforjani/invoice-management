<?php

namespace App\Filament\Resources\Payments\Tables;

use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class PaymentsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                    ->label('رقم الدفع')
                    ->sortable()
                    ->searchable(),

                TextColumn::make('amount')
                    ->label('القيمة')
                    ->money('LYD')
                    ->sortable(),

                TextColumn::make('created_at')
                    ->label('تاريخ الدفع')
                    ->dateTime()
                    ->sortable(),
            ])
            ->headerActions([
                CreateAction::make(),
            ])
            ->recordActions([
                DeleteAction::make(),
            ])
            ->defaultSort('created_at', 'desc');
    }
}
