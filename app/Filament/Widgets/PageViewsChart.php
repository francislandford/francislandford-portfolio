<?php

namespace App\Filament\Widgets;

use App\Models\PageView;
use BezhanSalleh\FilamentShield\Traits\HasWidgetShield;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Carbon;

class PageViewsChart extends ChartWidget
{
    use HasWidgetShield;

    protected ?string $heading = 'Page Views (Last 30 Days)';

    protected function getData(): array
    {
        $days = collect(range(29, 0))->map(fn (int $daysAgo) => now()->subDays($daysAgo)->toDateString());

        $counts = PageView::query()
            ->selectRaw('DATE(viewed_at) as date, COUNT(*) as count')
            ->where('viewed_at', '>=', now()->subDays(29)->startOfDay())
            ->groupBy('date')
            ->pluck('count', 'date');

        return [
            'datasets' => [
                [
                    'label' => 'Page Views',
                    'data' => $days->map(fn (string $day) => $counts->get($day, 0))->all(),
                    'borderColor' => '#f59e0b',
                    'backgroundColor' => 'rgba(245, 158, 11, 0.1)',
                    'fill' => true,
                ],
            ],
            'labels' => $days->map(fn (string $day) => Carbon::parse($day)->format('M j'))->all(),
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
