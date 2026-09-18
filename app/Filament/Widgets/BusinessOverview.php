<?php

namespace App\Filament\Widgets;

use App\Models\ContactMessage;
use App\Models\Enrollment;
use App\Models\Payment;
use App\Models\UsageLog;
use BezhanSalleh\FilamentShield\Traits\HasWidgetShield;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class BusinessOverview extends StatsOverviewWidget
{
    use HasWidgetShield;

    protected function getStats(): array
    {
        $revenue = Payment::where('status', 'successful')->sum('amount');
        $aiSpendThisMonth = UsageLog::whereBetween('created_at', [now()->startOfMonth(), now()->endOfMonth()])->sum('cost');
        $aiSpendAllTime = UsageLog::sum('cost');

        return [
            Stat::make('Revenue', '$'.number_format($revenue, 2))
                ->description(Payment::where('status', 'successful')->count().' successful payments')
                ->color('success'),

            Stat::make('AI Spend (This Month)', '$'.number_format($aiSpendThisMonth, 4))
                ->description('$'.number_format($aiSpendAllTime, 4).' all time')
                ->color('warning'),

            Stat::make('Course Enrollments', Enrollment::count())
                ->description(Enrollment::where('status', 'completed')->count().' completed')
                ->color('info'),

            Stat::make('Unread Messages', ContactMessage::where('is_read', false)->count())
                ->description(ContactMessage::count().' total')
                ->color(ContactMessage::where('is_read', false)->count() > 0 ? 'danger' : 'gray'),
        ];
    }
}
