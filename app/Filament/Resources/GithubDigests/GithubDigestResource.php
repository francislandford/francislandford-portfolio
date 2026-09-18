<?php

namespace App\Filament\Resources\GithubDigests;

use App\Filament\Resources\GithubDigests\Pages\EditGithubDigest;
use App\Filament\Resources\GithubDigests\Pages\ListGithubDigests;
use App\Filament\Resources\GithubDigests\Schemas\GithubDigestForm;
use App\Filament\Resources\GithubDigests\Tables\GithubDigestsTable;
use App\Models\GithubDigest;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

/**
 * Digests are produced by GithubDigestGenerator (see the "Generate New
 * Digest" header action), never created by hand.
 */
class GithubDigestResource extends Resource
{
    protected static ?string $model = GithubDigest::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedCodeBracket;

    protected static string|UnitEnum|null $navigationGroup = 'System';

    public static function form(Schema $schema): Schema
    {
        return GithubDigestForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return GithubDigestsTable::configure($table);
    }

    public static function canCreate(): bool
    {
        return false;
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListGithubDigests::route('/'),
            'edit' => EditGithubDigest::route('/{record}/edit'),
        ];
    }
}
