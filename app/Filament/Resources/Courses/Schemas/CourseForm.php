<?php

namespace App\Filament\Resources\Courses\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\MarkdownEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class CourseForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Content')
                    ->columns(2)
                    ->schema([
                        TextInput::make('title')
                            ->required()
                            ->columnSpanFull(),
                        TextInput::make('slug')
                            ->helperText('Leave blank to auto-generate from the title.')
                            ->columnSpanFull(),
                        TextInput::make('excerpt')
                            ->default(null)
                            ->columnSpanFull(),
                        MarkdownEditor::make('description')
                            ->required()
                            ->columnSpanFull(),
                        MarkdownEditor::make('body')
                            ->default(null)
                            ->columnSpanFull(),
                        FileUpload::make('cover_image')
                            ->image()
                            ->disk('public')
                            ->directory('courses')
                            ->visibility('public'),
                    ]),

                Section::make('Pricing')
                    ->columns(2)
                    ->schema([
                        TextInput::make('price')
                            ->numeric()
                            ->default(null)
                            ->prefix('$')
                            ->helperText('Leave blank for a free course.'),
                        TextInput::make('currency')
                            ->required()
                            ->default('USD'),
                    ]),

                Section::make('Publishing')
                    ->columns(3)
                    ->schema([
                        Select::make('level')
                            ->options([
                                'Beginner' => 'Beginner',
                                'Intermediate' => 'Intermediate',
                                'Advanced' => 'Advanced',
                            ])
                            ->default(null),
                        Select::make('status')
                            ->options(['draft' => 'Draft', 'published' => 'Published'])
                            ->default('draft')
                            ->required(),
                        TextInput::make('order')
                            ->required()
                            ->numeric()
                            ->default(0),
                    ]),

                Section::make('SEO')
                    ->columns(2)
                    ->collapsible()
                    ->collapsed()
                    ->schema([
                        TextInput::make('meta_title')
                            ->default(null)
                            ->columnSpanFull(),
                        TextInput::make('meta_description')
                            ->default(null)
                            ->columnSpanFull(),
                        FileUpload::make('og_image')
                            ->image()
                            ->disk('public')
                            ->directory('courses')
                            ->visibility('public')
                            ->helperText('Optional. Falls back to the cover image when sharing this course.')
                            ->columnSpanFull(),
                    ]),
            ]);
    }
}
