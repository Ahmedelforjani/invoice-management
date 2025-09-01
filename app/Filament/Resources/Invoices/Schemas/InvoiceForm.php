<?php

namespace App\Filament\Resources\Invoices\Schemas;

use App\Enums\InvoiceStatus;
use App\Filament\Resources\Customers\Schemas\CustomerForm;
use App\Models\Customer;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class InvoiceForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('تفاصيل الفاتورة')
                    ->description('أدخل تفاصيل الفاتورة.')
                    ->schema([
                        Select::make('customer_id')
                            ->label('الزبون')
                            ->relationship('customer')
                            ->getOptionLabelFromRecordUsing(fn(Customer $record) => "{$record->name} ({$record->phone})")
                            ->createOptionForm(fn(Schema $schema) => CustomerForm::configure($schema))
                            ->required()
                            ->searchable(['name', 'phone'])
                            ->preload(),

                        DatePicker::make('issue_date')
                            ->label('تاريخ الفاتورة')
                            ->default(now())
                            ->required(),

                        Select::make('status')
                            ->label('الحالة')
                            ->default(InvoiceStatus::ISSUED)
                            ->options(InvoiceStatus::class)
                            ->required(),
                    ])
                    ->columns(2),

                Section::make('اصناف الفاتورة')
                    ->description('أضف صنف إلى هذه الفاتورة.')
                    ->schema([
                        Repeater::make('items')
                            ->relationship()
                            ->schema([
                                TextInput::make('description')
                                    ->label('الاصناف')
                                    ->required()
                                    ->columnSpan(2),

                                TextInput::make('quantity')
                                    ->label('الكمية')
                                    ->numeric()
                                    ->default(1)
                                    ->minValue(1)
                                    ->required()
                                    ->live(onBlur: true)
                                    ->afterStateUpdated(function ($state, $set, $get) {
                                        $unitPrice = $get('unit_price') ?? 0;
                                        $set('total_price', $state * $unitPrice);
                                        self::updateTotals($set, $get);
                                    }),

                                TextInput::make('unit_price')
                                    ->label('سعر الوحدة')
                                    ->numeric()
                                    ->suffix('د.ل')
                                    ->required()
                                    ->live(onBlur: true)
                                    ->afterStateUpdated(function ($state, $set, $get) {
                                        $quantity = $get('quantity') ?? 1;
                                        $set('total_price', $state * $quantity);
                                        self::updateTotals($set, $get);
                                    }),

                                TextInput::make('total_price')
                                    ->label('السعر الكلي')
                                    ->numeric()
                                    ->suffix('د.ل')
                                    ->disabled()
                                    ->dehydrated(),
                            ])
                            ->columns(4)
                            ->defaultItems(1)
                            ->addActionLabel('إضافة صنف')
                            ->reorderableWithButtons()
                            ->collapsible()
                            ->live()
                            ->afterStateUpdated(function ($state, $set, $get) {
                                self::updateTotals($set, $get);
                            })
                            ->deleteAction(
                                fn($action) => $action->after(fn($set, $get) => self::updateTotals($set, $get))
                            ),
                    ]),

                Section::make('قيمة الفاتورة')
                    ->schema([
                        TextInput::make('subtotal')
                            ->label('المجموع')
                            ->numeric()
                            ->suffix('د.ل')
                            ->disabled()
                            ->dehydrated(),

                        TextInput::make('discount')
                            ->label('الخصم')
                            ->numeric()
                            ->default(0)
                            ->suffix('د.ل')
                            ->live(onBlur: true)
                            ->afterStateUpdated(function ($state, $set, $get) {
                                self::updateTotals($set, $get);
                            }),

                        TextInput::make('total')
                            ->label('المبلغ الإجمالي')
                            ->numeric()
                            ->suffix('د.ل')
                            ->disabled()
                            ->dehydrated(),
                    ])
                    ->columns(3),

                Section::make('دفعة أولية')
                    ->relationship('initialPayment')
                    ->schema([
                        TextInput::make('amount')
                            ->label('القيمة')
                            ->numeric()
                            ->suffix('د.ل')
                            ->required()
                            ->minValue(1)
                            ->maxValue(fn($state, $set, $get) => $get('total')),
                    ])
                    ->hiddenOn('edit'),

                Section::make('معلومات إضافية')
                    ->schema([
                        Textarea::make('notes')
                            ->label('ملاحظات')
                            ->placeholder('أدخل أي ملاحظات إضافية')
                            ->rows(3),
                    ]),
            ])->columns(1);
    }

    public static function updateTotals($set, $get): void
    {
        // Get all items
        $items = $get('items') ?? [];

        // Calculate subtotal from all items
        $subtotal = collect($items)->sum(function ($item) {
            $quantity = (float) ($item['quantity'] ?? 0);
            $unitPrice = (float) ($item['unit_price'] ?? 0);
            return $quantity * $unitPrice;
        });

        // Get discount percentage
        $discount = ($get('discount') ?? 0);

        // Calculate total
        $total = $subtotal - $discount;

        // Update the fields
        $set('subtotal', number_format($subtotal, 2, '.', ''));
        $set('total', number_format($total, 2, '.', ''));
    }
}
