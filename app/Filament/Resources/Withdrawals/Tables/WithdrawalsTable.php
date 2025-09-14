<?php

namespace App\Filament\Resources\Withdrawals\Tables;

use App\Filament\Resources\Employees\EmployeeResource;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
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

                TextColumn::make('employee.name')
                    ->label('سحب بواسطة')
                    ->description(fn($record) => $record->employee->phone)
                    ->url(fn($record) => EmployeeResource::getUrl('view', ['record' => $record->employee]))
                    ->sortable()
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
