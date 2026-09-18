<?php

namespace App\Services\Ai;

use Anthropic\Client;
use App\Contracts\BillableService;
use App\Models\BudgetCap;
use App\Support\LogsUsage;
use RuntimeException;

class AnthropicDigestService implements BillableService
{
    use LogsUsage;

    public function billableServiceName(): string
    {
        return 'anthropic_github_digest';
    }

    /**
     * @param  string[]  $activityLines
     */
    public function generate(array $activityLines): string
    {
        $apiKey = config('services.anthropic.api_key');

        if (empty($apiKey)) {
            throw new RuntimeException('ANTHROPIC_API_KEY is not configured.');
        }

        if (empty($activityLines)) {
            throw new RuntimeException('No recent public GitHub activity to summarize.');
        }

        if ($this->budgetExceeded()) {
            throw new RuntimeException('The monthly AI budget for this service has been reached.');
        }

        $model = config('services.anthropic.model', 'claude-opus-5');

        $client = new Client(apiKey: $apiKey);

        $message = $client->messages->create(
            model: $model,
            maxTokens: 300,
            system: "You write a short, upbeat \"what I've been working on\" digest (3-5 sentences) for a software engineer's portfolio site, based on their recent public GitHub activity. Group related items naturally, mention specific repos/projects by name, and write in first person as the engineer. Don't just list events mechanically — synthesize them into a narrative. Return only the digest text, no preamble or labels.",
            messages: [
                ['role' => 'user', 'content' => "Recent GitHub activity (newest first):\n".implode("\n", $activityLines)],
            ],
        );

        $digest = '';

        foreach ($message->content as $block) {
            if ($block->type === 'text') {
                $digest .= $block->text;
            }
        }

        $this->logUsage(
            action: 'generate_github_digest',
            cost: AnthropicPricing::cost($model, $message->usage->inputTokens, $message->usage->outputTokens),
            tokensUsed: $message->usage->inputTokens + $message->usage->outputTokens,
            meta: [
                'model' => $model,
                'input_tokens' => $message->usage->inputTokens,
                'output_tokens' => $message->usage->outputTokens,
                'events_summarized' => count($activityLines),
            ],
        );

        return trim($digest);
    }

    private function budgetExceeded(): bool
    {
        $cap = BudgetCap::where('service', $this->billableServiceName())->first();

        return $cap?->isExceeded() ?? false;
    }
}
