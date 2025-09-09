<?php

namespace App\Filament\Resources\Customers\Tables;

use App\Models\Customer;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
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
                    ->counts('invoices'),

                TextColumn::make('invoices_sum_remaining')
                    ->label('اجمالي المتبقي')
                    ->suffix(' د.ل')
                    ->state(fn(Customer $record) => $record->invoices->sum(fn ($item) => $item->total_amount - $item->paid_amount)),

                ToggleColumn::make('settings.show_total_remaining_in_invoice')
                    ->label('عرض المتبقي في الفاتورة؟'),

                TextColumn::make('created_at')
                    ->label('تاريخ الاضافة')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(),
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
