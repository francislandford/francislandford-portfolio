<?php

namespace App\Filament\Resources\BudgetCaps\Pages;

use App\Filament\Resources\BudgetCaps\BudgetCapResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListBudgetCaps extends ListRecords
{
    protected static string $resource = BudgetCapResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
