<?php

namespace App\Filament\Resources\BudgetCaps;

use App\Filament\Resources\BudgetCaps\Pages\CreateBudgetCap;
use App\Filament\Resources\BudgetCaps\Pages\EditBudgetCap;
use App\Filament\Resources\BudgetCaps\Pages\ListBudgetCaps;
use App\Filament\Resources\BudgetCaps\Schemas\BudgetCapForm;
use App\Filament\Resources\BudgetCaps\Tables\BudgetCapsTable;
use App\Models\BudgetCap;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class BudgetCapResource extends Resource
{
    protected static ?string $model = BudgetCap::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedBanknotes;

    protected static string|UnitEnum|null $navigationGroup = 'E-Learning';

    public static function form(Schema $schema): Schema
    {
        return BudgetCapForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return BudgetCapsTable::configure($table);
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
            'index' => ListBudgetCaps::route('/'),
            'create' => CreateBudgetCap::route('/create'),
            'edit' => EditBudgetCap::route('/{record}/edit'),
        ];
    }
}
