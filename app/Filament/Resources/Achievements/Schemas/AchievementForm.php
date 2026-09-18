<?php

namespace App\Filament\Resources\Achievements\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class AchievementForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('title')
                    ->required(),
                Textarea::make('description')
                    ->default(null)
                    ->columnSpanFull(),
                Select::make('category')
                    ->options([
                        'award' => 'Award',
                        'milestone' => 'Milestone',
                        'recognition' => 'Recognition',
                        'speaking' => 'Speaking',
                        'community' => 'Community',
                    ])
                    ->default('award')
                    ->required(),
                TextInput::make('organization')
                    ->default(null),
                DatePicker::make('date'),
                TextInput::make('url')
                    ->url()
                    ->default(null),
                TextInput::make('order')
                    ->required()
                    ->numeric()
                    ->default(0),
            ]);
    }
}
