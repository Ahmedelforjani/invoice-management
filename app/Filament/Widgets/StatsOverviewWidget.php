<?php

namespace App\Filament\Widgets;

use App\Filament\Resources\Customers\CustomerResource;
use App\Filament\Resources\Invoices\InvoiceResource;
use App\Models\Customer;
use App\Models\Expense;
use App\Models\Invoice;
use Filament\Support\Icons\Heroicon;
use Filament\Widgets\StatsOverviewWidget as BaseStatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverviewWidget extends BaseStatsOverviewWidget
{
    protected function getStats(): array
    {
        $invoiceQuery = Invoice::query()->notCancelled();

        return [
            Stat::make("صافي الربح", $invoiceQuery->selectRaw('SUM(total_amount - total_cost) as net_profit')->value('net_profit') ?? 0)
                ->icon(Heroicon::OutlinedBanknotes),

            Stat::make("إجمالي المستحق", $invoiceQuery->selectRaw('SUM(total_amount - paid_amount) as remaining')->value('remaining') ?? 0)
                ->icon(HeroIcon::OutlinedChartPie),

            Stat::make("إجمالي المقبوض", $invoiceQuery->sum('paid_amount'))
                ->icon(HeroIcon::OutlinedArrowTrendingUp),

            Stat::make("إجمالي قيمة الفواتير", $invoiceQuery->sum('total_amount'))
                ->icon(InvoiceResource::getNavigationIcon()),

            Stat::make("إجمالي المصروفات", Expense::sum('amount'))
                ->icon(HeroIcon::OutlinedCurrencyDollar),

            Stat::make('عدد الزبائن', Customer::count())
                ->icon(CustomerResource::getNavigationIcon())
                ->url(CustomerResource::getUrl()),

            Stat::make("عدد الفواتير", Invoice::count())
                ->icon(InvoiceResource::getNavigationIcon())
                ->url(InvoiceResource::getUrl()),

//            Stat::make("إجمالي تكلفة الفواتير", $invoiceQuery->sum('total_cost'))
//                ->icon('heroicon-o-currency-dollar')
//                ->color('danger'),


        ];
    }
}
