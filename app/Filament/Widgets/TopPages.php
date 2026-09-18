<?php

namespace App\Filament\Widgets;

use App\Models\PageView;
use BezhanSalleh\FilamentShield\Traits\HasWidgetShield;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;
use Illuminate\Database\Eloquent\Builder;

class TopPages extends TableWidget
{
    use HasWidgetShield;

    protected static ?string $heading = 'Top Pages (Last 30 Days)';

    protected int|string|array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->query(fn (): Builder => PageView::query()
                ->selectRaw('MIN(id) as id, path, COUNT(*) as views, COUNT(DISTINCT visitor_hash) as unique_visitors')
                ->where('viewed_at', '>=', now()->subDays(30))
                ->groupBy('path')
                ->orderByDesc('views'))
            ->paginated([5, 10, 25])
            ->defaultPaginationPageOption(10)
            ->columns([
                TextColumn::make('path')
                    ->label('Page'),
                TextColumn::make('views')
                    ->sortable(),
                TextColumn::make('unique_visitors')
                    ->label('Unique Visitors')
                    ->sortable(),
            ]);
    }
}
