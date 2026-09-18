<?php

namespace App\Filament\Resources\ChatConversations\RelationManagers;

use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

/**
 * Read-only transcript: messages are written by visitors and the chatbot, never by an admin.
 */
class MessagesRelationManager extends RelationManager
{
    protected static string $relationship = 'messages';

    public function form(Schema $schema): Schema
    {
        return $schema->components([]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('content')
            ->defaultSort('created_at')
            ->columns([
                TextColumn::make('role')
                    ->badge()
                    ->color(fn (string $state) => $state === 'user' ? 'gray' : 'warning'),
                TextColumn::make('content')
                    ->wrap()
                    ->searchable(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable(),
            ])
            ->headerActions([])
            ->recordActions([])
            ->toolbarActions([]);
    }
}
