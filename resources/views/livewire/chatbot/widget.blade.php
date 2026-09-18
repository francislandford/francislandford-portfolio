<div class="fixed bottom-6 right-6 z-50">
    @if($open)
        <div class="mb-4 flex h-[28rem] w-80 flex-col overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-2xl sm:w-96">
            <div class="flex items-center justify-between bg-slate-950 px-4 py-3">
                <div>
                    <p class="text-sm font-semibold text-white">Ask My Portfolio</p>
                    <p class="text-xs text-slate-400">Ask me anything about Francis's work</p>
                </div>
                <button wire:click="toggle" class="text-slate-400 hover:text-white" aria-label="Close chat">
                    <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path d="M6.28 5.22a.75.75 0 00-1.06 1.06L8.94 10l-3.72 3.72a.75.75 0 101.06 1.06L10 11.06l3.72 3.72a.75.75 0 101.06-1.06L11.06 10l3.72-3.72a.75.75 0 00-1.06-1.06L10 8.94 6.28 5.22z" /></svg>
                </button>
            </div>

            <div class="flex-1 space-y-3 overflow-y-auto p-4" x-data x-init="$el.scrollTop = $el.scrollHeight" x-effect="$el.scrollTop = $el.scrollHeight">
                @if(empty($messages))
                    <p class="text-sm text-slate-500">Hi! Ask me about Francis's projects, services, or experience.</p>
                @endif

                @foreach($messages as $message)
                    <div class="flex {{ $message['role'] === 'user' ? 'justify-end' : 'justify-start' }}">
                        <div class="max-w-[85%] rounded-2xl px-3 py-2 text-sm {{ $message['role'] === 'user' ? 'bg-amber-500 text-slate-950' : 'bg-slate-100 text-slate-700' }}">
                            {{ $message['content'] }}
                        </div>
                    </div>
                @endforeach

                <div wire:loading wire:target="send" class="flex justify-start">
                    <div class="rounded-2xl bg-slate-100 px-3 py-2 text-sm text-slate-400">Thinking…</div>
                </div>

                @if($error)
                    <p class="rounded-lg bg-red-50 px-3 py-2 text-xs text-red-600">{{ $error }}</p>
                @endif
            </div>

            <form wire:submit="send" class="flex items-center gap-2 border-t border-slate-200 p-3">
                <input
                    type="text"
                    wire:model="question"
                    placeholder="Type a question…"
                    class="flex-1 rounded-full border border-slate-300 px-4 py-2 text-sm focus:border-amber-500 focus:outline-none focus:ring-1 focus:ring-amber-500"
                />
                <button
                    type="submit"
                    wire:loading.attr="disabled"
                    wire:target="send"
                    class="rounded-full bg-slate-900 p-2.5 text-white hover:bg-amber-600 disabled:opacity-60"
                    aria-label="Send"
                >
                    <svg class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor"><path d="M3.105 2.289a.75.75 0 00-.826.95l1.414 4.925A1.5 1.5 0 005.135 9.25h6.115a.75.75 0 010 1.5H5.135a1.5 1.5 0 00-1.442 1.086l-1.414 4.926a.75.75 0 00.826.95 28.896 28.896 0 0015.293-7.154.75.75 0 000-1.115A28.897 28.897 0 003.105 2.289z" /></svg>
                </button>
            </form>
        </div>
    @endif

    <button
        wire:click="toggle"
        class="flex h-14 w-14 items-center justify-center rounded-full bg-amber-500 text-slate-950 shadow-xl hover:bg-amber-400"
        aria-label="Toggle chat"
    >
        @if($open)
            <svg class="h-6 w-6" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M14.77 12.79a.75.75 0 01-1.06.02L10 9.06l-3.71 3.75a.75.75 0 11-1.08-1.04l4.25-4.5a.75.75 0 011.08 0l4.25 4.5a.75.75 0 01-.02 1.06z" clip-rule="evenodd" /></svg>
        @else
            <svg class="h-6 w-6" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 2c-4.418 0-8 3.134-8 7 0 1.85.822 3.535 2.172 4.79-.145 1.005-.53 1.868-.913 2.502a.75.75 0 00.752 1.14 8.87 8.87 0 004.11-1.548c.607.096 1.234.146 1.879.146 4.418 0 8-3.134 8-7s-3.582-7-8-7z" clip-rule="evenodd" /></svg>
        @endif
    </button>
</div>
