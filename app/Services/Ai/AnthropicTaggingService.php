<?php

namespace App\Services\Ai;

use Anthropic\Client;
use App\Contracts\BillableService;
use App\Models\BudgetCap;
use App\Models\Category;
use App\Models\Post;
use App\Models\Tag;
use App\Support\LogsUsage;
use Illuminate\Support\Str;
use RuntimeException;

class AnthropicTaggingService implements BillableService
{
    use LogsUsage;

    public function billableServiceName(): string
    {
        return 'anthropic_blog_tagging';
    }

    /**
     * @return array{tags: string[], category: ?string}
     */
    public function suggest(Post $post): array
    {
        $apiKey = config('services.anthropic.api_key');

        if (empty($apiKey)) {
            throw new RuntimeException('ANTHROPIC_API_KEY is not configured.');
        }

        if ($this->budgetExceeded()) {
            throw new RuntimeException('The monthly AI budget for this service has been reached.');
        }

        $model = config('services.anthropic.model', 'claude-opus-5');

        $existingTags = Tag::query()->pluck('name')->implode(', ') ?: 'none yet';
        $existingCategories = Category::where('type', 'blog')->pluck('name')->implode(', ') ?: 'none yet';

        $client = new Client(apiKey: $apiKey);

        $message = $client->messages->create(
            model: $model,
            maxTokens: 300,
            system: <<<SYSTEM
                You categorize blog posts for a software engineer's portfolio site. Given a post's title and body, respond with ONLY a compact JSON object (no markdown fences, no preamble) shaped exactly like:

                {"tags": ["Tag One", "Tag Two"], "category": "Category Name"}

                Rules:
                - 2 to 5 tags, each a short topic label (e.g. "Laravel", "Offline-First", "System Design").
                - Prefer reusing an existing tag or category over inventing a near-duplicate.
                - Exactly one category, a broad subject area, title case.
                - Existing tags: {$existingTags}
                - Existing blog categories: {$existingCategories}
                SYSTEM,
            messages: [
                ['role' => 'user', 'content' => "Title: {$post->title}\n\nBody:\n{$post->body}"],
            ],
        );

        $text = '';

        foreach ($message->content as $block) {
            if ($block->type === 'text') {
                $text .= $block->text;
            }
        }

        $this->logUsage(
            action: 'tag_post',
            cost: AnthropicPricing::cost($model, $message->usage->inputTokens, $message->usage->outputTokens),
            tokensUsed: $message->usage->inputTokens + $message->usage->outputTokens,
            meta: [
                'model' => $model,
                'input_tokens' => $message->usage->inputTokens,
                'output_tokens' => $message->usage->outputTokens,
            ],
            subject: $post->exists ? $post : null,
        );

        return $this->parseResponse($text);
    }

    /**
     * @return array{tags: string[], category: ?string}
     */
    private function parseResponse(string $text): array
    {
        $json = Str::of($text)->trim()->betweenFirst('{', '}');
        $decoded = json_decode('{'.$json.'}', true);

        if (! is_array($decoded)) {
            throw new RuntimeException("Could not parse the AI's response as JSON.");
        }

        $tags = array_values(array_filter(array_map('trim', $decoded['tags'] ?? [])));
        $category = isset($decoded['category']) ? trim((string) $decoded['category']) : null;

        return [
            'tags' => $tags,
            'category' => $category ?: null,
        ];
    }

    private function budgetExceeded(): bool
    {
        $cap = BudgetCap::where('service', $this->billableServiceName())->first();

        return $cap?->isExceeded() ?? false;
    }
}
