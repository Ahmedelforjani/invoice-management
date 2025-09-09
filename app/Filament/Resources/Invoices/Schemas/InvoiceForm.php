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
                            ->hiddenOn('create')
                            ->required(),


                        Textarea::make('notes')
                            ->label('ملاحظات')
                            ->placeholder('أدخل أي ملاحظات إضافية')
                            ->rows(3)
                            ->columnSpanFull(),
                    ])
                    ->columns(2),

                Section::make('اصناف الفاتورة')
                    ->description('أضف اصناف الفاتورة.')
                    ->schema([
                        Repeater::make('items')
                            ->label('الاصناف')
                            ->relationship()
                            ->schema([
                                TextInput::make('description')
                                    ->label('الصنف')
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
                                        $set('total', $state * $unitPrice);
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
                                        $set('total', $state * $quantity);
                                        self::updateTotals($set, $get);
                                    }),

                                TextInput::make('total')
                                    ->label('السعر الكلي')
                                    ->numeric()
                                    ->suffix('د.ل')
                                    ->disabled()
                                    ->dehydrated(),
                            ])
                            ->columns(5)
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

                Section::make('مشتريات الفاتورة')
                    ->description('أضف اصناف الشراء إلى هذه الفاتورة ان وجدت.')
                    ->schema([
                        Repeater::make('purchaseItems')
                            ->label('اصناف الشراء')
                            ->relationship()
                            ->schema([
                                TextInput::make('description')
                                    ->label('صنف شراء')
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
                                        $set('total', $state * $unitPrice);
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
                                        $set('total', $state * $quantity);
                                        self::updateTotals($set, $get);
                                    }),

                                TextInput::make('total')
                                    ->label('التكلفة الكلية')
                                    ->numeric()
                                    ->suffix('د.ل')
                                    ->disabled()
                                    ->dehydrated(),
                            ])
                            ->columns(5)
                            ->defaultItems(0)
                            ->addActionLabel('إضافة صنف شراء')
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
                        TextInput::make('subtotal_amount')
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

                        TextInput::make('total_amount')
                            ->label('المبلغ الإجمالي')
                            ->numeric()
                            ->suffix('د.ل')
                            ->disabled()
                            ->dehydrated(),

                        TextInput::make('total_cost')
                            ->label('اجمالي التكلفة')
                            ->numeric()
                            ->suffix('د.ل')
                            ->disabled()
                            ->dehydrated(),

                        TextInput::make('net_profit')
                            ->label('الصافي')
                            ->numeric()
                            ->suffix('د.ل')
                            ->disabled()
                            ->dehydrated(false),
                    ])
                    ->columns(3),

                Section::make('دفعة أولية (عربون)')
                    ->relationship('initialPayment', fn (array $state): bool => filled($state['amount'] ?? null))
                    ->schema([
                        TextInput::make('amount')
                            ->label('القيمة')
                            ->numeric()
                            ->suffix('د.ل')
                            ->minValue(1)
                            ->maxValue(fn($state, $set, $get) => $get('total')),
                    ])
                    ->hiddenOn('edit'),
            ])->columns(1);
    }

    public static function updateTotals($set, $get): void
    {
        // Get all items
        $items = $get('items') ?? [];
        $purchaseItems = $get('purchaseItems') ?? [];

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

        $totalCost = collect($purchaseItems)->sum(function ($item) {
            $quantity = (float) ($item['quantity'] ?? 0);
            $unitPrice = (float) ($item['unit_price'] ?? 0);
            return $quantity * $unitPrice;
        });

        $netProfit = $total - $totalCost;

        // Update the fields
        $set('subtotal_amount', number_format($subtotal, 2, '.', ''));
        $set('total_amount', number_format($total, 2, '.', ''));
        $set('total_cost', number_format($totalCost, 2, '.', ''));
        $set('net_profit', number_format($netProfit, 2, '.', ''));
    }
}
