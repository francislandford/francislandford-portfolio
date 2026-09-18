<?php

namespace App\Filament\Resources\TrustedCompanies\Pages;

use App\Filament\Resources\TrustedCompanies\TrustedCompanyResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditTrustedCompany extends EditRecord
{
    protected static string $resource = TrustedCompanyResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
