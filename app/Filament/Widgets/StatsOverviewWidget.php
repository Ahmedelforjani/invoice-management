<?php

namespace App\Filament\Widgets;

use App\Filament\Resources\Customers\CustomerResource;
use App\Filament\Resources\Invoices\InvoiceResource;
use App\Models\Customer;
use App\Models\Expense;
use App\Models\Invoice;
use App\Models\Payment;
use Filament\Widgets\StatsOverviewWidget as BaseStatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverviewWidget extends BaseStatsOverviewWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('عدد الزبائن', Customer::count())
                ->icon(CustomerResource::getNavigationIcon())
                ->url(CustomerResource::getUrl()),

            Stat::make("عدد الفواتير", Invoice::count())
                ->icon(InvoiceResource::getNavigationIcon())
                ->url(InvoiceResource::getUrl()),

            Stat::make("إجمالي المبلغ المدفوع", Invoice::sum('paid_amount'))
                ->icon('heroicon-o-currency-dollar')
                ->color('success'),

            Stat::make("إجمالي قيمة الفواتير", Invoice::sum('total'))
                ->icon('heroicon-o-currency-dollar'),

            Stat::make("إجمالي المستحق", Invoice::selectRaw('SUM(total - paid_amount) as remaining')->value('remaining') ?? 0)
                ->icon('heroicon-o-currency-dollar')
                ->color('danger'),

            Stat::make("إجمالي المصروفات", Expense::sum('amount'))
                ->icon('heroicon-o-currency-dollar')
                ->color('danger'),
        ];
    }
}
