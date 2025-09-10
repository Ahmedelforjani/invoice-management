<?php

namespace App\Filament\Resources\Invoices\Pages;

use App\Filament\Resources\Invoices\InvoiceResource;
use Filament\Actions;
use Filament\Infolists\Components\TextEntry;
use Filament\Resources\Pages\ViewRecord;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Enums\TextSize;

class ViewInvoice extends ViewRecord
{
    protected static string $resource = InvoiceResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
            Actions\Action::make('print')
                ->label('طباعة')
                ->icon('heroicon-o-printer')
                ->color('success')
                ->url(fn($record) => route('invoices.print', $record))
                ->openUrlInNewTab(),
        ];
    }

    public function infolist(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('تفاصيل')
                    ->schema([
                        TextEntry::make('id')
                            ->label('رقم الفاتورة')
                            ->size(TextSize::Large),

                        TextEntry::make('customer.name')
                            ->label('العميل'),

                        TextEntry::make('status')
                            ->label('الحالة')
                            ->badge(),

                        TextEntry::make('issue_date')
                            ->label('تاريخ الفاتورة')
                            ->date(),

                        TextEntry::make('items_count')
                            ->counts('items')
                            ->label('عدد اصناف الفاتورة'),


                        TextEntry::make('notes')
                            ->label('ملاحظات')
                            ->placeholder('لا يوجد ملاحظات')
                            ->columnSpanFull(),
                    ])
                    ->columns(2),

                Section::make('قيمة الفاتورة')
                    ->schema([
                        Grid::make(1)
                            ->schema([
                                TextEntry::make('subtotal_amount')
                                    ->label('المجموع')
                                    ->suffix(' د.ل')
                                    ->formatStateUsing(fn($state) => number_format($state, 2)),

                                TextEntry::make('discount')
                                    ->label('الخصم')
                                    ->suffix(' د.ل')
                                    ->formatStateUsing(fn($state) => number_format($state, 2)),

                                TextEntry::make('total_amount')
                                    ->label('المبلغ الإجمالي')
                                    ->suffix(' د.ل')
                                    ->formatStateUsing(fn($state) => number_format($state, 2))
                                    ->weight('bold'),

                                TextEntry::make('paid_amount')
                                    ->label('المدفوع')
                                    ->suffix(' د.ل')
                                    ->formatStateUsing(fn($state) => number_format($state, 2))
                                    ->color('success')
                                    ->weight('bold')
                                    ->size(TextSize::Large),

                                TextEntry::make('remaining')
                                    ->getStateUsing(fn($record) => $record->total_amount - $record->paid_amount)
                                    ->label('المتبقي')
                                    ->suffix(' د.ل')
                                    ->formatStateUsing(fn($state) => number_format($state, 2))
                                    ->weight('bold')
                                    ->color(fn($state) => $state > 0 ? 'danger' : 'success'),
                            ]),
                        Grid::make(1)
                            ->schema([
                                TextEntry::make('total_cost')
                                    ->label('تكلفة الفاتورة')
                                    ->suffix(' د.ل')
                                    ->formatStateUsing(fn($state) => number_format($state, 2))
                                    ->weight('bold'),

                                TextEntry::make('net_profit')
                                    ->getStateUsing(fn($record) => $record->total_amount - $record->total_cost)
                                    ->label('الصافي')
                                    ->suffix(' د.ل')
                                    ->formatStateUsing(fn($state) => number_format($state, 2))
                                    ->weight('bold')
                                    ->color(fn($state) => $state < 0 ? 'danger' : 'success'),
                                ]),
                    ])->columns(),
            ]);
    }
}
