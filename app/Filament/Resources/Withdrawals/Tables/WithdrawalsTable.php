<?php

namespace App\Filament\Resources\Withdrawals\Tables;

use App\Filament\Resources\Employees\EmployeeResource;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\Textarea;
use Filament\Support\Colors\Color;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class WithdrawalsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                    ->label('#')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('description')
                    ->label('وصف السحب')
                    ->limit(50)
                    ->tooltip(function (TextColumn $column): ?string {
                        $state = $column->getState();
                        if (strlen($state) <= 50) {
                            return null;
                        }
                        return $state;
                    })
                    ->color(Color::Gray)
                    ->searchable(),

                TextColumn::make('amount')->label('القيمة')
                    ->suffix(' د.ل')
                    ->formatStateUsing(fn($state) => number_format($state, 2))
                    ->sortable(),

                TextColumn::make('withdrawal_date')
                    ->label('تاريخ السحب')
                    ->date()
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
            ])
            ->defaultSort('withdrawal_date', 'desc');
    }
}
