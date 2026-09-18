<?php

namespace App\Services\Ai;

use Anthropic\Client;
use App\Models\BudgetCap;
use App\Contracts\BillableService;
use App\Support\LogsUsage;
use RuntimeException;

class AnthropicProjectDescriptionService implements BillableService
{
    use LogsUsage;

    public function billableServiceName(): string
    {
        return 'anthropic_project_description';
    }

    /**
     * @return array{description: string, body: string}
     */
    public function generate(?string $title, string $sourceContent): array
    {
        $apiKey = config('services.anthropic.api_key');

        if (empty($apiKey)) {
            throw new RuntimeException('ANTHROPIC_API_KEY is not configured.');
        }

        if (trim($sourceContent) === '') {
            throw new RuntimeException('No README or source content was provided.');
        }

        if ($this->budgetExceeded()) {
            throw new RuntimeException('The monthly AI budget for this service has been reached.');
        }

        $model = config('services.anthropic.model', 'claude-opus-5');

        $client = new Client(apiKey: $apiKey);

        // Delimiter-based output (not JSON): README/source content routinely
        // contains braces, quotes, and code fences that are painful to
        // safely round-trip through JSON escaping in a model's raw text output.
        $message = $client->messages->create(
            model: $model,
            maxTokens: 800,
            system: <<<'SYSTEM'
                You write project write-ups for a software engineer's portfolio site, based on a project's README or source content. Plain text only — no markdown formatting, no code fences.

                Produce two things:
                1. A short description: 1-2 plain sentences, suitable as a card/preview blurb.
                2. A body: 2-4 short paragraphs covering what the project does, the technical approach, and technologies used. Write in third person about the engineer who built it.

                Only state what the source material actually supports — never invent client names, metrics, outcomes, or features not evidenced in the content.

                Respond in EXACTLY this format and nothing else:

                DESCRIPTION:
                <description text>

                BODY:
                <body text>
                SYSTEM,
            messages: [
                ['role' => 'user', 'content' => "Project title: {$title}\n\nSource content:\n{$sourceContent}"],
            ],
        );

        $text = '';

        foreach ($message->content as $block) {
            if ($block->type === 'text') {
                $text .= $block->text;
            }
        }

        $this->logUsage(
            action: 'generate_project_description',
            cost: AnthropicPricing::cost($model, $message->usage->inputTokens, $message->usage->outputTokens),
            tokensUsed: $message->usage->inputTokens + $message->usage->outputTokens,
            meta: [
                'model' => $model,
                'input_tokens' => $message->usage->inputTokens,
                'output_tokens' => $message->usage->outputTokens,
            ],
        );

        return $this->parseResponse($text);
    }

    /**
     * @return array{description: string, body: string}
     */
    private function parseResponse(string $text): array
    {
        if (! preg_match('/DESCRIPTION:\s*(.*?)\s*BODY:\s*(.*)/is', $text, $matches)) {
            throw new RuntimeException("Could not parse the AI's response.");
        }

        return [
            'description' => trim($matches[1]),
            'body' => trim($matches[2]),
        ];
    }

    private function budgetExceeded(): bool
    {
        $cap = BudgetCap::where('service', $this->billableServiceName())->first();

        return $cap?->isExceeded() ?? false;
    }
}
