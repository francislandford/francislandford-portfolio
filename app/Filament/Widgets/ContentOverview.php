<?php

namespace App\Filament\Widgets;

use App\Models\Post;
use App\Models\Project;
use App\Models\Service;
use BezhanSalleh\FilamentShield\Traits\HasWidgetShield;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class ContentOverview extends StatsOverviewWidget
{
    use HasWidgetShield;

    protected function getStats(): array
    {
        return [
            Stat::make('Published Projects', Project::where('status', 'published')->count())
                ->description(Project::count().' total')
                ->color('success'),

            Stat::make('Published Services', Service::where('is_active', true)->count())
                ->description(Service::count().' total')
                ->color('success'),

            Stat::make('Published Posts', Post::where('status', 'published')->count())
                ->description(Post::count().' total')
                ->color('success'),

            Stat::make('Total Blog Views', number_format(Post::sum('views_count')))
                ->description('Across all posts')
                ->color('gray'),
        ];
    }
}
