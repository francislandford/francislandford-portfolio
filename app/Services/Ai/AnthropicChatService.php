<?php

namespace App\Services\Ai;

use Anthropic\Client;
use App\Contracts\BillableService;
use App\Models\BudgetCap;
use App\Models\ChatConversation;
use App\Support\LogsUsage;
use RuntimeException;

class AnthropicChatService implements BillableService
{
    use LogsUsage;

    public function __construct(private readonly PortfolioRetriever $retriever) {}

    public function billableServiceName(): string
    {
        return 'anthropic_portfolio_chat';
    }

    /**
     * @param  array<int, array{role: string, content: string}>  $history  Prior turns, oldest first.
     */
    public function reply(array $history, string $question, ?ChatConversation $conversation = null): string
    {
        $apiKey = config('services.anthropic.api_key');

        if (empty($apiKey)) {
            throw new RuntimeException('ANTHROPIC_API_KEY is not configured.');
        }

        if ($this->budgetExceeded()) {
            throw new RuntimeException("This chatbot's monthly budget has been reached. Please use the contact form instead.");
        }

        $model = config('services.anthropic.model', 'claude-opus-5');

        $context = $this->buildContext($question);

        $client = new Client(apiKey: $apiKey);

        $messages = [];

        foreach ($history as $turn) {
            $messages[] = ['role' => $turn['role'], 'content' => $turn['content']];
        }

        $messages[] = ['role' => 'user', 'content' => $question];

        $message = $client->messages->create(
            model: $model,
            maxTokens: 500,
            system: <<<SYSTEM
                You are "Ask My Portfolio", a helpful assistant answering visitor questions about Francis Landford, a software engineer, using ONLY the context below. Be concise (2-4 sentences), friendly, and specific.

                If the context doesn't cover what's being asked, say you don't have that information and suggest the visitor use the Contact page — never invent projects, experience, or facts not present in the context.

                --- CONTEXT ---
                {$context}
                --- END CONTEXT ---
                SYSTEM,
            messages: $messages,
        );

        $answer = '';

        foreach ($message->content as $block) {
            if ($block->type === 'text') {
                $answer .= $block->text;
            }
        }

        $this->logUsage(
            action: 'chat_reply',
            cost: AnthropicPricing::cost($model, $message->usage->inputTokens, $message->usage->outputTokens),
            tokensUsed: $message->usage->inputTokens + $message->usage->outputTokens,
            meta: [
                'model' => $model,
                'input_tokens' => $message->usage->inputTokens,
                'output_tokens' => $message->usage->outputTokens,
            ],
            subject: $conversation,
        );

        return trim($answer);
    }

    private function buildContext(string $question): string
    {
        $sections = [$this->retriever->siteProfile()];

        foreach ($this->retriever->retrieve($question) as $chunk) {
            $sections[] = "[{$chunk['type']}] {$chunk['title']}\n{$chunk['content']}\nLink: {$chunk['url']}";
        }

        return implode("\n\n", $sections);
    }

    private function budgetExceeded(): bool
    {
        $cap = BudgetCap::where('service', $this->billableServiceName())->first();

        return $cap?->isExceeded() ?? false;
    }
}
