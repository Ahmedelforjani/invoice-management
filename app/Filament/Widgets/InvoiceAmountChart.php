<?php

namespace App\Filament\Widgets;

use App\Models\Invoice;
use Filament\Widgets\ChartWidget;
use Flowframe\Trend\Trend;
use Flowframe\Trend\TrendValue;

class InvoiceAmountChart extends ChartWidget
{
    protected static ?int $sort = 4;
    public ?string $filter = 'month';
    protected ?string $heading = 'قيمة الفواتير';

    protected function getFilters(): ?array
    {
        return [
            'week' => 'اخر اسبوع',
            'month' => 'اخر شهر',
            'year' => 'السنة الحالية',
        ];
    }

    protected function getData(): array
    {
        $startFrom = match ($this->filter) {
            'week' => now()->subDays(7),
            'month' => now()->subDays(30),
            'year' => now()->startOfYear(),
            default => now()->startOfDay(),
        };

        $query = Trend::query(Invoice::query()->notCancelled())
            ->dateColumn('issue_date')
            ->between(start: $startFrom, end: now());

        if ($this->filter === 'week') {
            $query->perDay();
        } elseif ($this->filter === 'month') {
            $query->perDay();
        } elseif ($this->filter === 'year') {
            $query->perMonth();
        } else {
            $query->perHour();
        }

        $data = $query->sum('total_amount');

        return [
            'datasets' => [
                [
                    'label' => 'قيمة الفاتورة',
                    'data' => $data->map(fn(TrendValue $value) => $value->aggregate),
                    'fill' => true,
                    'tension' => 0.4,
                ],
            ],
            'labels' => $data->map(fn(TrendValue $value) => $this->formatDate($value->date)),
        ];
    }

    private function formatDate($date): string
    {
        return match ($this->filter) {
            'week', 'month' => date('M d', strtotime($date), ),
            'year' => date('M', strtotime($date)),
            default => date('h A', strtotime($date)),
        };
    }

    protected function getType(): string
    {
        return 'line';
    }
}
