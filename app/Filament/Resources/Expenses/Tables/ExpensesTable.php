<?php

namespace App\Filament\Resources\Expenses\Tables;

use App\Filament\Resources\Employees\EmployeeResource;
use App\Filament\Resources\Employees\RelationManagers\ExpensesRelationManager;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Support\Colors\Color;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class ExpensesTable
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
                    ->label('صرف إلى')
                    ->description(fn($record) => $record->employee->phone)
                    ->url(fn($record) => EmployeeResource::getUrl('view', ['record' => $record->employee]))
                    ->sortable()
                    ->hiddenOn(ExpensesRelationManager::class)
                    ->searchable(),

                TextColumn::make('description')
                    ->label('وصف المصروف')
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

                TextColumn::make('amount')
                    ->label('القيمة')
                    ->suffix(' د.ل')
                    ->formatStateUsing(fn($state) => number_format($state, 2))
                    ->sortable(),

                TextColumn::make('expense_date')
                    ->label('تاريخ المصروف')
                    ->date()
                    ->sortable(),

                TextColumn::make('created_at')
                    ->label('تاريخ الاضافة')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
            ])
            ->defaultSort('expense_date', 'desc');
    }
}
