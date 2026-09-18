<?php

namespace App\Filament\Widgets;

use App\Models\PageView;
use BezhanSalleh\FilamentShield\Traits\HasWidgetShield;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class VisitorOverview extends StatsOverviewWidget
{
    use HasWidgetShield;

    protected function getStats(): array
    {
        $today = PageView::whereDate('viewed_at', today())->count();
        $uniqueVisitors30d = PageView::where('viewed_at', '>=', now()->subDays(30))
            ->distinct('visitor_hash')
            ->count('visitor_hash');

        $topPage = PageView::where('viewed_at', '>=', now()->subDays(30))
            ->selectRaw('path, COUNT(*) as views')
            ->groupBy('path')
            ->orderByDesc('views')
            ->first();

        return [
            Stat::make('Page Views Today', $today)
                ->color('success'),

            Stat::make('Unique Visitors (30d)', $uniqueVisitors30d)
                ->description(PageView::where('viewed_at', '>=', now()->subDays(30))->count().' page views')
                ->color('info'),

            Stat::make('Total Page Views', number_format(PageView::count()))
                ->description('All time')
                ->color('gray'),

            Stat::make('Top Page (30d)', $topPage?->path ?? '—')
                ->description($topPage ? $topPage->views.' views' : 'No data yet')
                ->color('warning'),
        ];
    }
}
