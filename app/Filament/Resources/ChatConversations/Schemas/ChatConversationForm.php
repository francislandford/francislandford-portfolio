<?php

namespace App\Filament\Resources\ChatConversations\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class ChatConversationForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('session_id')
                    ->disabled(),
            ]);
    }
}
