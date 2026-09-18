<?php

namespace App\Filament\Pages;

use App\Models\Setting as SettingModel;
use BackedEnum;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use UnitEnum;

class Settings extends Page
{
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedCog6Tooth;

    protected static string|UnitEnum|null $navigationGroup = 'System';

    protected static ?int $navigationSort = -1;

    protected string $view = 'filament.pages.settings';

    /**
     * @var array<string, mixed>
     */
    public array $data = [];

    public function mount(): void
    {
        $this->form->fill(
            SettingModel::query()->pluck('value', 'key')->all()
        );
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Identity')
                    ->description('Who you are, as shown across the site.')
                    ->columns(2)
                    ->schema([
                        TextInput::make('name')
                            ->label('Full name')
                            ->required(),
                        TextInput::make('tagline')
                            ->label('Tagline')
                            ->required()
                            ->helperText('Shown under your name in the hero and header.'),
                        TextInput::make('github_username')
                            ->label('GitHub username')
                            ->helperText('Used for the GitHub activity digest and profile link.'),
                    ]),

                Section::make('Homepage Hero')
                    ->description('The main headline visitors see first.')
                    ->schema([
                        TextInput::make('hero_headline')
                            ->label('Headline')
                            ->required(),
                        Textarea::make('hero_subheadline')
                            ->label('Subheadline')
                            ->rows(3)
                            ->required(),
                    ]),

                Section::make('Contact')
                    ->description('How people reach you, shown in the footer and contact page.')
                    ->columns(2)
                    ->schema([
                        TextInput::make('email')
                            ->label('Email address')
                            ->email()
                            ->required(),
                        TextInput::make('phone')
                            ->label('Phone number')
                            ->tel(),
                        TextInput::make('website')
                            ->label('Website URL')
                            ->url(),
                        TextInput::make('address')
                            ->label('Address'),
                    ]),

                Section::make('Homepage Stats')
                    ->description('The four stat cards on the homepage.')
                    ->columns(4)
                    ->schema([
                        TextInput::make('stat_years_experience')
                            ->label('Years of experience'),
                        TextInput::make('stat_projects_completed')
                            ->label('Projects completed'),
                        TextInput::make('stat_happy_clients')
                            ->label('Happy clients'),
                        TextInput::make('stat_awards')
                            ->label('Awards'),
                    ]),

                Section::make('SEO')
                    ->description('Used for search engines and social link previews.')
                    ->schema([
                        Textarea::make('site_meta_description')
                            ->label('Site meta description')
                            ->rows(3)
                            ->required(),
                    ]),
            ])
            ->statePath('data');
    }

    public function save(): void
    {
        $state = $this->form->getState();

        foreach ($state as $key => $value) {
            SettingModel::set($key, (string) $value);
        }

        Notification::make()
            ->success()
            ->title('Settings saved')
            ->send();
    }
}
