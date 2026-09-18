<?php

namespace App\Filament\Resources\BudgetCaps\Pages;

use App\Filament\Resources\BudgetCaps\BudgetCapResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditBudgetCap extends EditRecord
{
    protected static string $resource = BudgetCapResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
