<?php

namespace App\Filament\Resources\BudgetCaps\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class BudgetCapForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('service')
                    ->required(),
                TextInput::make('monthly_limit')
                    ->numeric()
                    ->default(null),
                TextInput::make('notify_at_percent')
                    ->required()
                    ->numeric()
                    ->default(80),
                Toggle::make('is_enabled')
                    ->required(),
            ]);
    }
}
