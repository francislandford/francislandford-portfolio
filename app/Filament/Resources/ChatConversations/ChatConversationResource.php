<?php

namespace App\Filament\Resources\ChatConversations;

use App\Filament\Resources\ChatConversations\Pages\EditChatConversation;
use App\Filament\Resources\ChatConversations\Pages\ListChatConversations;
use App\Filament\Resources\ChatConversations\RelationManagers\MessagesRelationManager;
use App\Filament\Resources\ChatConversations\Schemas\ChatConversationForm;
use App\Filament\Resources\ChatConversations\Tables\ChatConversationsTable;
use App\Models\ChatConversation;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

/**
 * Conversations are created by visitors using the chat widget, never by hand.
 * The edit page exists only to host the read-only message transcript.
 */
class ChatConversationResource extends Resource
{
    protected static ?string $model = ChatConversation::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedChatBubbleLeftEllipsis;

    protected static string|UnitEnum|null $navigationGroup = 'Engagement';

    public static function form(Schema $schema): Schema
    {
        return ChatConversationForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ChatConversationsTable::configure($table);
    }

    public static function canCreate(): bool
    {
        return false;
    }

    public static function getRelations(): array
    {
        return [
            MessagesRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListChatConversations::route('/'),
            'edit' => EditChatConversation::route('/{record}/edit'),
        ];
    }
}
