<?php

namespace App\Filament\Resources\Lessons\Schemas;

use Filament\Forms\Components\MarkdownEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Schema;

class LessonForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('course_id')
                    ->relationship('course', 'title')
                    ->searchable()
                    ->preload()
                    ->required(),
                TextInput::make('title')
                    ->required(),
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
                    ->required()
                    ->numeric()
                    ->default(0),
                Toggle::make('is_free_preview')
                    ->required(),
            ]);
    }
}
