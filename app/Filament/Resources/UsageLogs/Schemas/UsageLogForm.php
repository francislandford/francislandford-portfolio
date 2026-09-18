<?php

namespace App\Filament\Resources\UsageLogs\Schemas;

use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class UsageLogForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('service')
                    ->required(),
                TextInput::make('action')
                    ->default(null),
                TextInput::make('tokens_used')
                    ->numeric()
                    ->default(null),
                TextInput::make('cost')
                    ->required()
                    ->numeric()
                    ->default(0.0)
                    ->prefix('$'),
                TextInput::make('currency')
                    ->required()
                    ->default('USD'),
                TextInput::make('subject_type')
                    ->default(null),
                TextInput::make('subject_id')
                    ->numeric()
                    ->default(null),
                Textarea::make('meta')
                    ->default(null)
                    ->columnSpanFull(),
            ]);
    }
}
