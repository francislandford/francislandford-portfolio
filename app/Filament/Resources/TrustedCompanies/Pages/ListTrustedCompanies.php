<?php

namespace App\Filament\Resources\TrustedCompanies\Pages;

use App\Filament\Resources\TrustedCompanies\TrustedCompanyResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListTrustedCompanies extends ListRecords
{
    protected static string $resource = TrustedCompanyResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
