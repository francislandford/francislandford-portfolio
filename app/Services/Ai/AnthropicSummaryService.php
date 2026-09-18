<?php

namespace App\Services\Ai;

use Anthropic\Client;
use App\Contracts\BillableService;
use App\Models\BudgetCap;
use App\Models\Post;
use App\Support\LogsUsage;
use RuntimeException;

class AnthropicSummaryService implements BillableService
{
    use LogsUsage;

    public function billableServiceName(): string
    {
        return 'anthropic_blog_summary';
    }

    public function summarize(Post $post): string
    {
        $apiKey = config('services.anthropic.api_key');

        if (empty($apiKey)) {
            throw new RuntimeException('ANTHROPIC_API_KEY is not configured.');
        }

        if ($this->budgetExceeded()) {
            throw new RuntimeException('The monthly AI budget for this service has been reached.');
        }

        $model = config('services.anthropic.model', 'claude-opus-5');

        $client = new Client(apiKey: $apiKey);

        $message = $client->messages->create(
            model: $model,
            maxTokens: 300,
            system: "You write concise, engaging TL;DR summaries (2-3 sentences) of blog posts for a software engineer's portfolio site. Return only the summary text, with no preamble or labels.",
            messages: [
                ['role' => 'user', 'content' => "Title: {$post->title}\n\nBody:\n{$post->body}"],
            ],
        );

        $summary = '';

        foreach ($message->content as $block) {
            if ($block->type === 'text') {
                $summary .= $block->text;
            }
        }

        $this->logUsage(
            action: 'summarize_post',
            cost: AnthropicPricing::cost($model, $message->usage->inputTokens, $message->usage->outputTokens),
            tokensUsed: $message->usage->inputTokens + $message->usage->outputTokens,
            meta: [
                'model' => $model,
                'input_tokens' => $message->usage->inputTokens,
                'output_tokens' => $message->usage->outputTokens,
            ],
            subject: $post->exists ? $post : null,
        );

        return trim($summary);
    }

    private function budgetExceeded(): bool
    {
        $cap = BudgetCap::where('service', $this->billableServiceName())->first();

        return $cap?->isExceeded() ?? false;
    }
}
