<?php

namespace App\Filament\Resources\GithubDigests\Pages;

use App\Filament\Resources\GithubDigests\GithubDigestResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditGithubDigest extends EditRecord
{
    protected static string $resource = GithubDigestResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
