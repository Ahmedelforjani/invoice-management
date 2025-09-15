<?php

namespace App\Filament\Widgets;

use App\Enums\InvoiceStatus;
use App\Filament\Resources\Customers\CustomerResource;
use App\Filament\Resources\Invoices\InvoiceResource;
use App\Models\Customer;
use App\Models\Expense;
use App\Models\Invoice;
use App\Models\Withdrawal;
use Filament\Support\Icons\Heroicon;
use Filament\Widgets\StatsOverviewWidget as BaseStatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Number;

class StatsOverviewWidget extends BaseStatsOverviewWidget
{
    protected function getStats(): array
    {
        $invoiceQuery = Invoice::query()->notCancelled();
        $netProfit = $invoiceQuery
            ->clone()
            ->onlyPaid()
            ->selectRaw('SUM(total_amount - total_cost) as net_profit')
            ->value('net_profit');

        $withdrawalsTotal = Withdrawal::sum('amount');
        $remainingProfits = $netProfit - $withdrawalsTotal;

        $unearnedProfit = $invoiceQuery
            ->clone()
            ->onlyIssued()
            ->selectRaw('SUM(total_amount - total_cost) as unearned_profit')
            ->value('unearned_profit');

        $remaining = $invoiceQuery
            ->clone()
            ->selectRaw('SUM(total_amount - paid_amount) as remaining')
            ->value('remaining');

        return [
            Stat::make("الارباح المتبقية", Number::format($remainingProfits ?? 0))
                ->icon(Heroicon::OutlinedBanknotes)
                ->url(InvoiceResource::getUrl(null, ['filters[status][value]' => InvoiceStatus::PAID])),

            Stat::make("الارباح", Number::format($netProfit ?? 0))
                ->icon(Heroicon::OutlinedBanknotes)
                ->url(InvoiceResource::getUrl(null, ['filters[status][value]' => InvoiceStatus::PAID])),

            Stat::make("الارباح الغير محصلة", Number::format($unearnedProfit ?? 0))
                ->icon(Heroicon::OutlinedClipboardDocument)
                ->url(InvoiceResource::getUrl(null, ['filters[status][value]' => InvoiceStatus::ISSUED])),

            Stat::make("إجمالي المستحق", Number::format($remaining ?? 0))
                ->icon(HeroIcon::OutlinedChartPie),

            Stat::make("إجمالي المقبوض", Number::format($invoiceQuery->sum('paid_amount')))
                ->icon(HeroIcon::OutlinedArrowTrendingUp),

            Stat::make("إجمالي قيمة الفواتير", Number::format($invoiceQuery->sum('total_amount')))
                ->icon(InvoiceResource::getNavigationIcon()),

            Stat::make("إجمالي المصروفات", Number::format(Expense::sum('amount')))
                ->icon(HeroIcon::OutlinedCurrencyDollar),

            Stat::make('عدد الزبائن', Number::format(Customer::count()))
                ->icon(CustomerResource::getNavigationIcon())
                ->url(CustomerResource::getUrl()),

            Stat::make("عدد الفواتير", Number::format(Invoice::count()))
                ->icon(InvoiceResource::getNavigationIcon())
                ->url(InvoiceResource::getUrl()),

//            Stat::make("إجمالي تكلفة الفواتير", $invoiceQuery->sum('total_cost'))
//                ->icon('heroicon-o-currency-dollar')
//                ->color('danger'),


        ];
    }
}
