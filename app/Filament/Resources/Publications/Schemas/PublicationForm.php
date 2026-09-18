<?php

namespace App\Filament\Resources\Publications\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class PublicationForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('title')
                    ->required(),
                TextInput::make('publisher')
                    ->required(),
                Textarea::make('excerpt')
                    ->default(null)
                    ->columnSpanFull(),
                TextInput::make('url')
                    ->url()
                    ->required(),
                DatePicker::make('published_at'),
                TextInput::make('type')
                    ->default(null),
                TextInput::make('order')
                    ->required()
                    ->numeric()
                    ->default(0),
            ]);
    }
}
