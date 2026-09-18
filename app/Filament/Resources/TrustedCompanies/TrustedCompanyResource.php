<?php

namespace App\Filament\Resources\TrustedCompanies;

use App\Filament\Resources\TrustedCompanies\Pages\CreateTrustedCompany;
use App\Filament\Resources\TrustedCompanies\Pages\EditTrustedCompany;
use App\Filament\Resources\TrustedCompanies\Pages\ListTrustedCompanies;
use App\Filament\Resources\TrustedCompanies\Schemas\TrustedCompanyForm;
use App\Filament\Resources\TrustedCompanies\Tables\TrustedCompaniesTable;
use App\Models\TrustedCompany;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class TrustedCompanyResource extends Resource
{
    protected static ?string $model = TrustedCompany::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedBuildingOffice2;

    protected static string|UnitEnum|null $navigationGroup = 'Portfolio';

    public static function form(Schema $schema): Schema
    {
        return TrustedCompanyForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return TrustedCompaniesTable::configure($table);
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
            'index' => ListTrustedCompanies::route('/'),
            'create' => CreateTrustedCompany::route('/create'),
            'edit' => EditTrustedCompany::route('/{record}/edit'),
        ];
    }
}
