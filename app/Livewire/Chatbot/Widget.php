<?php

namespace App\Livewire\Chatbot;

use App\Models\ChatConversation;
use App\Services\Ai\AnthropicChatService;
use Illuminate\Support\Facades\Log;
use Livewire\Component;

class Widget extends Component
{
    private const MAX_MESSAGES = 20;

    public bool $open = false;
    public string $question = '';

    /** @var array<int, array{role: string, content: string}> */
    public array $messages = [];

    public ?string $error = null;

    public function mount(): void
    {
        // Read-only lookup: don't create a conversation row just because the
        // widget mounted on a page — only an actual sent message should.
        $conversation = ChatConversation::where('session_id', session()->getId())->first();

        $this->messages = $conversation?->messages
            ->map(fn ($m) => ['role' => $m->role, 'content' => $m->content])
            ->all() ?? [];
    }

    public function toggle(): void
    {
        $this->open = ! $this->open;
    }

    public function send(AnthropicChatService $chatService): void
    {
        $question = trim($this->question);

        if (blank($question)) {
            return;
        }

        $conversation = $this->currentConversation();

        if ($conversation->messages()->count() >= self::MAX_MESSAGES) {
            $this->error = "This conversation has reached its limit. Please reach out via the contact form for anything further.";

            return;
        }

        $this->error = null;
        $history = collect($this->messages)->take(-10)->values()->all();

        $this->question = '';
        $conversation->messages()->create(['role' => 'user', 'content' => $question]);
        $this->messages[] = ['role' => 'user', 'content' => $question];

        try {
            $answer = $chatService->reply($history, $question, $conversation);

            $conversation->messages()->create(['role' => 'assistant', 'content' => $answer]);
            $this->messages[] = ['role' => 'assistant', 'content' => $answer];
        } catch (\Throwable $e) {
            Log::warning('Portfolio chatbot error: '.$e->getMessage());

            $this->error = "Sorry, I couldn't process that just now. Please try again shortly or use the contact form.";
        }
    }

    private function currentConversation(): ChatConversation
    {
        return ChatConversation::firstOrCreate(['session_id' => session()->getId()]);
    }

    public function render()
    {
        return view('livewire.chatbot.widget');
    }
}
