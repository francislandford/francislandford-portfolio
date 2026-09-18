<?php

namespace App\Filament\Resources\Quizzes\RelationManagers;

use App\Filament\Resources\QuizQuestions\QuizQuestionResource;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class QuestionsRelationManager extends RelationManager
{
    protected static string $relationship = 'questions';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Textarea::make('question')
                    ->required()
                    ->columnSpanFull(),
                TextInput::make('order')
                    ->numeric()
                    ->default(0),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('question')
            ->defaultSort('order')
            ->columns([
                TextColumn::make('order')->sortable(),
                TextColumn::make('question')
                    ->searchable()
                    ->wrap()
                    ->limit(80),
                TextColumn::make('options_count')
                    ->label('Options')
                    ->counts('options')
                    ->badge(),
            ])
            ->headerActions([
                CreateAction::make(),
            ])
            ->recordActions([
                Action::make('manageOptions')
                    ->label('Manage Options')
                    ->icon(Heroicon::OutlinedListBullet)
                    ->url(fn ($record) => QuizQuestionResource::getUrl('edit', ['record' => $record])),
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
