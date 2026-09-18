<?php

namespace App\Filament\Resources\Courses\RelationManagers;

use App\Filament\Resources\Lessons\LessonResource;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\MarkdownEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class LessonsRelationManager extends RelationManager
{
    protected static string $relationship = 'lessons';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('title')
                    ->required()
                    ->maxLength(255),
                TextInput::make('slug')
                    ->helperText('Leave blank to auto-generate from the title.'),
                Select::make('type')
                    ->options([
                        'video' => 'Video',
                        'text' => 'Text',
                        'code' => 'Code',
                    ])
                    ->default('text')
                    ->live()
                    ->required(),
                TextInput::make('video_url')
                    ->label('Video URL')
                    ->url()
                    ->required(fn (Get $get) => $get('type') === 'video')
                    ->visible(fn (Get $get) => $get('type') === 'video')
                    ->default(null),
                MarkdownEditor::make('body')
                    ->required()
                    ->helperText('Use the code-block button (or ``` fences) for code snippets.')
                    ->columnSpanFull(),
                TextInput::make('order')
                    ->numeric()
                    ->default(0),
                Toggle::make('is_free_preview')
                    ->label('Free preview')
                    ->default(false),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('title')
            ->defaultSort('order')
            ->columns([
                TextColumn::make('order')->sortable(),
                TextColumn::make('title')
                    ->searchable(),
                TextColumn::make('type')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'video' => 'info',
                        'code' => 'warning',
                        default => 'gray',
                    }),
                IconColumn::make('is_free_preview')
                    ->label('Preview')
                    ->boolean(),
                TextColumn::make('attachments_count')
                    ->label('Resources')
                    ->counts('attachments')
                    ->badge(),
            ])
            ->headerActions([
                CreateAction::make(),
            ])
            ->recordActions([
                Action::make('manageResources')
                    ->label('Manage Resources')
                    ->icon(Heroicon::OutlinedPaperClip)
                    ->url(fn ($record) => LessonResource::getUrl('edit', ['record' => $record])),
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
