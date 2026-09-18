<?php

namespace App\Filament\Resources\GithubDigests\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class GithubDigestForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Textarea::make('content')
                    ->label('Digest')
                    ->helperText('Feel free to touch this up before it goes live.')
                    ->required()
                    ->columnSpanFull(),
                TextInput::make('events_count')
                    ->label('GitHub events summarized')
                    ->disabled()
                    ->numeric(),
                DateTimePicker::make('generated_at')
                    ->disabled(),
            ]);
    }
}
