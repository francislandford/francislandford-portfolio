<?php

namespace App\Filament\Resources\Projects\Schemas;

use App\Services\Ai\AnthropicProjectDescriptionService;
use App\Services\Ai\GitHubReadmeService;
use Filament\Actions\Action;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Notifications\Notification;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;

class ProjectForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('title')
                    ->required(),
                TextInput::make('slug')
                    ->helperText('Leave blank to auto-generate from the title.'),
                TextInput::make('client')
                    ->default(null),
                DatePicker::make('start_date'),
                TextInput::make('live_url')
                    ->url()
                    ->default(null),
                Textarea::make('description')
                    ->required()
                    ->columnSpanFull()
                    ->hintAction(
                        Action::make('generateFromReadme')
                            ->label('Generate from GitHub/README')
                            ->icon(Heroicon::OutlinedSparkles)
                            ->schema([
                                TextInput::make('repo_url')
                                    ->label('GitHub repo URL')
                                    ->placeholder('https://github.com/owner/repo')
                                    ->helperText('Leave blank if pasting README/source content below instead.'),
                                Textarea::make('readme_content')
                                    ->label('Or paste README / source content')
                                    ->rows(8),
                            ])
                            ->action(function (array $data, Get $get, Set $set) {
                                $content = trim($data['readme_content'] ?? '');

                                try {
                                    if ($content === '' && filled($data['repo_url'] ?? null)) {
                                        $content = app(GitHubReadmeService::class)->fetchReadme($data['repo_url']);
                                    }

                                    if ($content === '') {
                                        Notification::make()
                                            ->title('Provide a repo URL or paste some content first')
                                            ->warning()
                                            ->send();

                                        return;
                                    }

                                    $result = app(AnthropicProjectDescriptionService::class)
                                        ->generate($get('title'), $content);

                                    $set('description', $result['description']);
                                    $set('body', $result['body']);

                                    Notification::make()
                                        ->title('Description generated')
                                        ->success()
                                        ->send();
                                } catch (\Throwable $e) {
                                    Notification::make()
                                        ->title('Could not generate a description')
                                        ->body($e->getMessage())
                                        ->danger()
                                        ->send();
                                }
                            })
                    ),
                Textarea::make('body')
                    ->default(null)
                    ->columnSpanFull(),
                FileUpload::make('cover_image')
                    ->image()
                    ->disk('public')
                    ->directory('projects')
                    ->visibility('public'),
                Select::make('status')
                    ->options(['draft' => 'Draft', 'published' => 'Published'])
                    ->default('draft')
                    ->required(),
                Toggle::make('is_featured')
                    ->required(),
                TextInput::make('order')
                    ->required()
                    ->numeric()
                    ->default(0),
                TextInput::make('meta_title')
                    ->default(null),
                TextInput::make('meta_description')
                    ->default(null),
                FileUpload::make('og_image')
                    ->image()
                    ->disk('public')
                    ->directory('projects')
                    ->visibility('public')
                    ->helperText('Optional. Falls back to the cover image when sharing this project.'),
                Select::make('categories')
                    ->relationship('categories', 'name', fn ($query) => $query->where('type', 'project'))
                    ->multiple()
                    ->preload(),
                Select::make('skills')
                    ->relationship('skills', 'name')
                    ->multiple()
                    ->preload(),
            ]);
    }
}
