<?php

namespace App\Filament\Resources\Payments\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class PaymentForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('user_id')
                    ->relationship('user', 'name')
                    ->required(),
                Select::make('course_id')
                    ->relationship('course', 'title')
                    ->required(),
                Select::make('gateway')
                    ->options(['mtn_momo' => 'Mtn momo', 'orange_money' => 'Orange money'])
                    ->required(),
                TextInput::make('phone_number')
                    ->tel()
                    ->required(),
                TextInput::make('amount')
                    ->required()
                    ->numeric(),
                TextInput::make('currency')
                    ->required(),
                Select::make('status')
                    ->options(['pending' => 'Pending', 'successful' => 'Successful', 'failed' => 'Failed'])
                    ->default('pending')
                    ->required(),
                TextInput::make('gateway_reference')
                    ->default(null),
                Textarea::make('meta')
                    ->default(null)
                    ->columnSpanFull(),
            ]);
    }
}
