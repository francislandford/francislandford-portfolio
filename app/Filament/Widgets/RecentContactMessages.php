<?php

namespace App\Filament\Widgets;

use App\Models\ContactMessage;
use BezhanSalleh\FilamentShield\Traits\HasWidgetShield;
use Filament\Actions\Action;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;
use Illuminate\Database\Eloquent\Builder;

class RecentContactMessages extends TableWidget
{
    use HasWidgetShield;

    protected static ?string $heading = 'Recent Contact Messages';

    protected int|string|array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->query(fn (): Builder => ContactMessage::query()->latest())
            ->defaultPaginationPageOption(5)
            ->columns([
                TextColumn::make('name')
                    ->searchable(),
                TextColumn::make('email')
                    ->searchable(),
                TextColumn::make('subject')
                    ->limit(40),
                IconColumn::make('is_read')
                    ->boolean(),
                TextColumn::make('created_at')
                    ->since()
                    ->sortable(),
            ])
            ->recordActions([
                Action::make('view')
                    ->label('View')
                    ->url(fn (ContactMessage $record) => route('filament.admin.resources.contact-messages.edit', $record)),
            ]);
    }
}
