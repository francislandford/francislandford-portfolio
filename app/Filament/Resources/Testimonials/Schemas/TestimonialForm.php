<?php

namespace App\Filament\Resources\Testimonials\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class TestimonialForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('project_id')
                    ->relationship('project', 'title')
                    ->default(null),
                TextInput::make('client_name')
                    ->required(),
                TextInput::make('client_title')
                    ->default(null),
                TextInput::make('client_company')
                    ->default(null),
                FileUpload::make('avatar')
                    ->image()
                    ->disk('public')
                    ->directory('testimonials')
                    ->visibility('public'),
                Textarea::make('content')
                    ->required()
                    ->columnSpanFull(),
                TextInput::make('rating')
                    ->numeric()
                    ->default(null),
                Toggle::make('is_featured')
                    ->required(),
                TextInput::make('order')
                    ->required()
                    ->numeric()
                    ->default(0),
            ]);
    }
}
