<?php

namespace App\Filament\Resources\Posts\Schemas;

use App\Models\Category;
use App\Models\Post;
use App\Models\Tag;
use App\Services\Ai\AnthropicSummaryService;
use App\Services\Ai\AnthropicTaggingService;
use Filament\Actions\Action;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;

class PostForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('author_id')
                    ->relationship('author', 'name')
                    ->default(null),
                TextInput::make('title')
                    ->required(),
                TextInput::make('slug')
                    ->helperText('Leave blank to auto-generate from the title.'),
                TextInput::make('excerpt')
                    ->default(null),
                Textarea::make('body')
                    ->required()
                    ->columnSpanFull(),
                Textarea::make('ai_summary')
                    ->default(null)
                    ->columnSpanFull()
                    ->helperText('Shown as the post\'s TL;DR on the public site.')
                    ->hintAction(
                        Action::make('generateSummary')
                            ->label('Generate with AI')
                            ->icon(Heroicon::OutlinedSparkles)
                            ->action(function (Get $get, Set $set) {
                                $title = $get('title');
                                $body = $get('body');

                                if (blank($title) || blank($body)) {
                                    Notification::make()
                                        ->title('Add a title and body first')
                                        ->warning()
                                        ->send();

                                    return;
                                }

                                try {
                                    $summary = app(AnthropicSummaryService::class)
                                        ->summarize(new Post(['title' => $title, 'body' => $body]));

                                    $set('ai_summary', $summary);

                                    Notification::make()
                                        ->title('Summary generated')
                                        ->success()
                                        ->send();
                                } catch (\Throwable $e) {
                                    Notification::make()
                                        ->title('Could not generate summary')
                                        ->body($e->getMessage())
                                        ->danger()
                                        ->send();
                                }
                            })
                    ),
                FileUpload::make('cover_image')
                    ->image(),
                Select::make('status')
                    ->options(['draft' => 'Draft', 'published' => 'Published', 'scheduled' => 'Scheduled'])
                    ->default('draft')
                    ->required(),
                DateTimePicker::make('published_at'),
                TextInput::make('views_count')
                    ->required()
                    ->numeric()
                    ->default(0),
                TextInput::make('meta_title')
                    ->default(null),
                TextInput::make('meta_description')
                    ->default(null),
                FileUpload::make('og_image')
                    ->image(),
                Select::make('categories')
                    ->relationship('categories', 'name', fn ($query) => $query->where('type', 'blog'))
                    ->multiple()
                    ->preload(),
                Select::make('tags')
                    ->relationship('tags', 'name')
                    ->multiple()
                    ->preload()
                    ->hintAction(
                        Action::make('suggestTags')
                            ->label('Suggest with AI')
                            ->icon(Heroicon::OutlinedSparkles)
                            ->action(function (Get $get, Set $set) {
                                $title = $get('title');
                                $body = $get('body');

                                if (blank($title) || blank($body)) {
                                    Notification::make()
                                        ->title('Add a title and body first')
                                        ->warning()
                                        ->send();

                                    return;
                                }

                                try {
                                    $result = app(AnthropicTaggingService::class)
                                        ->suggest(new Post(['title' => $title, 'body' => $body]));

                                    $tagIds = collect($result['tags'])
                                        ->map(fn (string $name) => Tag::firstOrCreate(['name' => $name])->id);

                                    $set('tags', collect($get('tags'))->merge($tagIds)->unique()->values()->all());

                                    if ($result['category']) {
                                        $category = Category::firstOrCreate(['type' => 'blog', 'name' => $result['category']]);

                                        $set('categories', collect($get('categories'))->merge([$category->id])->unique()->values()->all());
                                    }

                                    Notification::make()
                                        ->title('Suggestions applied')
                                        ->body(implode(', ', $result['tags']))
                                        ->success()
                                        ->send();
                                } catch (\Throwable $e) {
                                    Notification::make()
                                        ->title('Could not generate suggestions')
                                        ->body($e->getMessage())
                                        ->danger()
                                        ->send();
                                }
                            })
                    ),
            ]);
    }
}
