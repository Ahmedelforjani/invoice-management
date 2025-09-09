<?php

namespace App\Filament\Widgets;

use App\Enums\InvoiceStatus;
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
        $invoiceQuery = Invoice::query()->whereNot('status', InvoiceStatus::CANCELLED);

        return [
            Stat::make('عدد الزبائن', Customer::count())
                ->icon(CustomerResource::getNavigationIcon())
                ->url(CustomerResource::getUrl()),

            Stat::make("عدد الفواتير", Invoice::count())
                ->icon(InvoiceResource::getNavigationIcon())
                ->url(InvoiceResource::getUrl()),

            Stat::make("إجمالي المبلغ المدفوع", $invoiceQuery->sum('paid_amount'))
                ->icon('heroicon-o-currency-dollar'),

            Stat::make("إجمالي قيمة الفواتير", $invoiceQuery->sum('total_amount'))
                ->icon('heroicon-o-currency-dollar'),

            Stat::make("إجمالي المستحق", $invoiceQuery->selectRaw('SUM(total_amount - paid_amount) as remaining')->value('remaining') ?? 0)
                ->icon('heroicon-o-currency-dollar'),

            Stat::make("إجمالي تكلفة الفواتير", $invoiceQuery->sum('total_cost'))
                ->icon('heroicon-o-currency-dollar')
                ->color('danger'),

            Stat::make("الصافي", $invoiceQuery->selectRaw('SUM(total_amount - total_cost) as net_profit')->value('net_profit') ?? 0)
                ->icon('heroicon-o-currency-dollar'),

            Stat::make("إجمالي المصروفات", Expense::sum('amount'))
                ->icon('heroicon-o-currency-dollar'),
        ];
    }
}
