<?php

namespace App\Filament\Resources\GithubDigests\Pages;

use App\Filament\Resources\GithubDigests\GithubDigestResource;
use App\Services\Ai\GithubDigestGenerator;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ListRecords;
use Filament\Support\Icons\Heroicon;

class ListGithubDigests extends ListRecords
{
    protected static string $resource = GithubDigestResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('generate')
                ->label('Generate New Digest')
                ->icon(Heroicon::OutlinedSparkles)
                ->action(function (GithubDigestGenerator $generator) {
                    try {
                        $generator->run();

                        Notification::make()
                            ->title('Digest generated')
                            ->success()
                            ->send();
                    } catch (\Throwable $e) {
                        Notification::make()
                            ->title('Could not generate digest')
                            ->body($e->getMessage())
                            ->danger()
                            ->send();
                    }
                }),
        ];
    }
}
