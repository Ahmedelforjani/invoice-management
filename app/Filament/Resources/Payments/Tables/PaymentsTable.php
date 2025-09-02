<?php

namespace App\Filament\Resources\Payments\Tables;

use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;

class PaymentsTable
{
    public static function configure(Table $table, ?Model $invoice = null): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                    ->label('رقم الدفع')
                    ->sortable()
                    ->searchable(),

                TextColumn::make('amount')
                    ->label('القيمة')
                    ->suffix(' د.ل')
                    ->formatStateUsing(fn($state) => number_format($state, 2))
                    ->sortable(),

                TextColumn::make('created_at')
                    ->label('تاريخ الدفع')
                    ->dateTime()
                    ->sortable(),
            ])
            ->headerActions([
                CreateAction::make()
                    ->hidden(
                        $invoice && $invoice->paid_amount >= $invoice->total
                    ),
            ])
            ->recordActions([
                DeleteAction::make(),
            ])
            ->defaultSort('created_at', 'desc');
    }
}
