<?php

namespace App\Filament\Resources\Certificates\Tables;

use Filament\Actions\ViewAction;
use Filament\Infolists\Components\TextEntry;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class CertificatesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('enrollment.user.name')
                    ->label('Student')
                    ->searchable(),
                TextColumn::make('enrollment.course.title')
                    ->label('Course')
                    ->searchable(),
                TextColumn::make('certificate_number')
                    ->searchable()
                    ->fontFamily('mono')
                    ->copyable(),
                TextColumn::make('issued_at')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                ViewAction::make()
                    ->schema([
                        TextEntry::make('enrollment.user.name')->label('Student'),
                        TextEntry::make('enrollment.user.email')->label('Email'),
                        TextEntry::make('enrollment.course.title')->label('Course'),
                        TextEntry::make('certificate_number')->label('Certificate No.')->fontFamily('mono')->copyable(),
                        TextEntry::make('issued_at')->label('Issued')->dateTime(),
                    ]),
            ])
            ->toolbarActions([
                //
            ]);
    }
}
