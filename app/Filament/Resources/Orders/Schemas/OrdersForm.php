<?php

    namespace App\Filament\Resources\Orders\Schemas;

    use App\Enums\OrderStatus;
    use App\Filament\Resources\Customers\Schemas\CustomerForm;
    use App\Models\Customer;
    use Filament\Forms\Components\DatePicker;
    use Filament\Forms\Components\Repeater;
    use Filament\Forms\Components\Select;
    use Filament\Forms\Components\Textarea;
    use Filament\Forms\Components\TextInput;
    use Filament\Schemas\Components\Section;
    use Filament\Schemas\Schema;

    class OrdersForm
    {
        public static function configure(Schema $schema): Schema
        {
            return $schema
                ->components([
                    Section::make('تفاصيل الطلبية')
                        ->description('قم بتعبئة تفاصيل الطلبية أدناه.')
                        ->schema([
                            DatePicker::make('order_date')
                                ->label('تاريخ الطلبية')
                                ->default(now())
                                ->required()
                                ->columnSpanFull(),
                            Select::make('status')
                                ->label('حالة الطلبية')
                                ->options(OrderStatus::class)
                                ->default('ordered')
                                ->required()
                                ->columnSpanFull(),

                            Textarea::make('notes')
                                ->label('ملاحظات')
                                ->rows(3)
                                ->placeholder('أدخل أي ملاحظات إضافية هنا...')
                                ->columnSpanFull(),
                        ])
                        ->columns(2),

                    Section::make('الطلبات')
                        ->description('إدارة الطلبات المرتبطة بهذه الطلبية.')
                        ->schema([
                            Repeater::make('customers')
                                ->label('الزبائن')
                                ->relationship('customers')
                                ->schema([
                                    Select::make('customer_id')
                                        ->label('الزبون')
                                        ->relationship('customer')
                                        ->getOptionLabelFromRecordUsing(fn(Customer $record) => "{$record->name} ({$record->phone})")
                                        ->createOptionForm(fn(Schema $schema) => CustomerForm::configure($schema))
                                        ->required()
                                        ->searchable(['name', 'phone'])
                                        ->columnSpanFull()
                                        ->preload(),

                                    Repeater::make('items')
                                        ->label('المنتجات')
                                        ->relationship('items')
                                        ->schema([
                                            TextInput::make('product_name')
                                                ->label('المنتج')
                                                ->required(),

                                            TextInput::make('quantity')
                                                ->label('الكمية')
                                                ->numeric()
                                                ->default(1)
                                                ->minValue(1)
                                                ->required()
                                                ->live(onBlur: true)
                                                ->afterStateUpdated(function ($state, callable $set, callable $get) {
                                                    $set('total', ($get('quantity') ?? 1) * ($get('unit_price') ?? 0));

                                                    self::updateCustomerTotals($set, $get);
                                                    self::updateTotals($set, $get);
                                                }),

                                            TextInput::make('unit_price')
                                                ->label('سعر الوحدة')
                                                ->numeric()
                                                ->suffix('د.ل')
                                                ->required()
                                                ->live(onBlur: true)
                                                ->afterStateUpdated(function ($state, $set, $get) {
                                                    $set('total', ($get('quantity') ?? 1) * $state);

                                                    self::updateCustomerTotals($set, $get);
                                                    self::updateTotals($set, $get);
                                                }),

                                            TextInput::make('cost_price')
                                                ->label('تكلفة الوحدة')
                                                ->numeric()
                                                ->suffix('د.ل')
                                                ->default(0)
                                                ->required()
                                                ->live(onBlur: true)
                                                ->afterStateUpdated(function ($state, $set, $get) {
                                                    self::updateCustomerTotals($set, $get);
                                                    self::updateTotals($set, $get);
                                                }),

                                            TextInput::make('total')
                                                ->label('الإجمالي')
                                                ->suffix('د.ل')
                                                ->disabled()
                                                ->dehydrated(),
                                        ])
                                        ->columns(5)
                                        ->afterStateUpdated(function($state, $set, $get){
                                            self::updateTotals($set, $get);
                                        })
                                        ->live()
                                        ->columnSpanFull()
                                        ->addActionLabel('إضافة المنتجات'),

                                    TextInput::make('discount_amount')
                                        ->label('الخصم')
                                        ->numeric()
                                        ->default(0)
                                        ->suffix('د.ل')
                                        ->required()
                                        ->live(onBlur: true)
                                        ->afterStateUpdated(function($state, $set, $get){
                                            $rootGet = fn($key) => $get('../../' . $key);
                                            $rootSet = fn($key, $value) => $set('../../' . $key, $value);
                                            self::updateTotals($rootSet, $rootGet);
                                        }),

                                    TextInput::make('paid_amount')
                                        ->label('عربون')
                                        ->numeric()
                                        ->default(0)
                                        ->suffix('د.ل')
                                        ->required(),

                                    TextInput::make('subtotal_amount')
                                        ->label('المجموع')
                                        ->numeric()
                                        ->suffix('د.ل')
                                        ->disabled()
                                        ->dehydrated(),

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
                                ])
                                ->addActionLabel('إضافة زبون')
                                ->reorderableWithButtons()
                                ->live()
                                ->afterStateUpdated(function($state, $set, $get){
                                    self::updateTotals($set, $get);
                                })
                                ->columns(5)
                                ->collapsible(),
                        ]),

                    Section::make('قيمة الطلبية')
                        ->description('إجمالي قيمة الطلبية مع التكاليف والخصومات.')
                        ->schema([
                            TextInput::make('subtotal_amount')
                                ->label('المجموع')
                                ->numeric()
                                ->suffix('د.ل')
                                ->disabled()
                                ->dehydrated(),

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
    //                    Section::make('دفعة أولية (عربون)')
    //                        ->description('إدارة الدفعة الأولية للطلبية.')
    //                        ->schema([
    //                            TextInput::make('paid_amount')
    //                                ->label('الدفعة الأولية')
    //                                ->numeric()
    //                                ->default(0)
    //                                ->suffix('د.ل')
    //                                ->required(),
    //                        ])
    //                        ->hiddenOn('edit')
                ])->columns(1);
        }

        public static function updateCustomerTotals($set, $get): void
        {
            $items = $get('../../items') ?? [];
            $discount = (float) ($get('../../discount_amount') ?? 0);

            $subtotal = 0;
            $totalCost = 0;

            foreach ($items as $item) {
                $qty = (float) ($item['quantity'] ?? 0);
                $unitPrice = (float) ($item['unit_price'] ?? 0);
                $costPrice = (float) ($item['cost_price'] ?? 0);

                $subtotal += $qty * $unitPrice;
                $totalCost += $qty * $costPrice;
            }

            $total = $subtotal - $discount;

            $set('../../subtotal_amount', number_format($subtotal, 2, '.', ''));
            $set('../../total_cost', number_format($totalCost, 2, '.', ''));
            $set('../../total_amount', number_format($total, 2, '.', ''));
        }

        public static function updateTotals($set, $get): void
        {
            $customers = $get('customers') ?? [];

            $orderSubtotal = 0;
            $orderTotalCost = 0;
            $totalDiscount = 0;

            foreach ($customers as $index => $customer) {
                $items = $customer['items'] ?? [];

                $customerSubtotal = 0;
                $customerTotalCost = 0;

                foreach ($items as $item) {
                    $qty = (float) ($item['quantity'] ?? 0);
                    $unitPrice = (float) ($item['unit_price'] ?? 0);
                    $costPrice = (float) ($item['cost_price'] ?? 0);

                    $customerSubtotal += $qty * $unitPrice;
                    $customerTotalCost += $qty * $costPrice;
                }

                $discount = (float) ($customer['discount_amount'] ?? 0);
                $customerTotal = $customerSubtotal - $discount;

                $set("customers.$index.subtotal_amount", number_format($customerSubtotal, 2, '.', ''));
                $set("customers.$index.total_cost", number_format($customerTotalCost, 2, '.', ''));
                $set("customers.$index.total_amount", number_format($customerTotal, 2, '.', ''));

                $orderSubtotal += $customerSubtotal;
                $orderTotalCost += $customerTotalCost;
                $totalDiscount += $discount;
            }

            $orderTotal = $orderSubtotal - $totalDiscount;
            $netProfit = $orderTotal - $orderTotalCost;

            $set('subtotal_amount', number_format($orderSubtotal, 2, '.', ''));
            $set('total_cost', number_format($orderTotalCost, 2, '.', ''));
            $set('total_amount', number_format($orderTotal, 2, '.', ''));
            $set('net_profit', number_format($netProfit, 2, '.', ''));
        }
    }
