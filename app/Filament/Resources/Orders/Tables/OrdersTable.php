<?php

namespace App\Filament\Resources\Orders\Tables;

use App\Enums\OrderStatus;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Notifications\Notification;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Collection;

class OrdersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')->searchable(true)
                    ->label('رقم الطلبية')
                    ->sortable()
                    ->copyable()
                    ->searchable(),
                TextColumn::make('status')
                    ->label('الحالة')
                    ->badge()
                    ->sortable(),

                TextColumn::make('total_amount')
                    ->label('المبلغ الإجمالي')
                    ->suffix(' د.ل')
                    ->formatStateUsing(fn($state) => number_format($state))
                    ->sortable(),

                TextColumn::make('shipping_cost')
                    ->label('تكلفة الشحن')
                    ->suffix(' د.ل')
                    ->formatStateUsing(fn($state) => number_format($state))
                    ->sortable(),

                TextColumn::make('created_at')
                    ->label('تاريخ الطلبية')
                    ->date()
                    ->sortable()

            ])
            ->filters([
                SelectFilter::make('status')
                    ->label('الحالة')
                    ->options(OrderStatus::class)
                    ->searchable()
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make()
                ]),
            ])
            ->defaultSort('created_at', 'desc');
    }
}
