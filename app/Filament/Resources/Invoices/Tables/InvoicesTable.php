<?php

namespace App\Filament\Resources\Invoices\Tables;

use App\Enums\InvoiceStatus;
use App\Filament\Resources\Customers\CustomerResource;
use App\Filament\Resources\Customers\RelationManagers\InvoicesRelationManager;
use Filament\Actions\Action;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\Summarizers\Sum;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Support\Number;

class InvoicesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                    ->label('رقم الفاتورة')
                    ->sortable()
                    ->copyable()
                    ->searchable(),

                TextColumn::make('customer.name')
                    ->label('الزبون')
                    ->description(fn($record) => $record->customer->phone)
                    ->sortable()
                    ->url(fn($record) => CustomerResource::getUrl('view', ['record' => $record->customer]))
                    ->hiddenOn(InvoicesRelationManager::class)
                    ->searchable(),

                TextColumn::make('status')
                    ->label('الحالة')
                    ->badge()
                    ->sortable(),

                TextColumn::make('total_amount')
                    ->label('المبلغ الإجمالي')
                    ->suffix(' د.ل')
                    ->formatStateUsing(fn($state) => Number::format($state))
                    ->sortable(),

                TextColumn::make('issue_date')
                    ->label('تاريخ الفاتورة')
                    ->date()
                    ->sortable(),

                TextColumn::make('paid_amount')
                    ->label('المدفوع')
                    ->suffix(' د.ل')
                    ->formatStateUsing(fn($state) => Number::format($state))
                    ->color('success')
                    ->sortable()
                    ->toggleable(),

                TextColumn::make('remaining')
                    ->label('المتبقى')
                    ->getStateUsing(fn($record) => $record->total_amount - $record->paid_amount)
                    ->suffix(' د.ل')
                    ->formatStateUsing(fn($state) => Number::format($state))
                    ->color(fn($state) => $state > 0 ? 'danger' : 'success')
                    ->toggleable(),

                TextColumn::make('net_profit')
                    ->label('الصافي')
                    ->getStateUsing(fn($record) => $record->total_amount - $record->total_cost)
                    ->suffix(' د.ل')
                    ->formatStateUsing(fn($state) => Number::format($state))
                    ->color('success')
                    ->toggleable(),

                TextColumn::make('created_at')
                    ->label('تاريخ الاضافة')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->label('الحالة')
                    ->options(InvoiceStatus::class),

                SelectFilter::make('customer')
                    ->label('الزبون')
                    ->relationship('customer', 'name')
                    ->searchable()
                    ->preload(),
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
                Action::make('print')
                    ->label('طباعة')
                    ->icon('heroicon-o-printer')
                    ->color('success')
                    ->url(fn($record) => route('invoices.print', $record))
                    ->openUrlInNewTab(),
            ])
            ->defaultSort('created_at', 'desc');
    }
}
