<div>
    <section class="border-b border-slate-200 bg-slate-50 py-10 dark:border-slate-800 dark:bg-slate-900/40">
        <div class="mx-auto max-w-3xl px-6">
            <a href="{{ route('elearning.show', $course->slug) }}" class="font-mono text-sm font-semibold text-amber-600 hover:text-amber-700 dark:text-amber-400 dark:hover:text-amber-300">&larr; {{ $course->title }}</a>
            <h1 class="mt-3 text-3xl font-bold text-slate-900 dark:text-white">{{ $course->quiz->title }}</h1>
            <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">Pass with {{ $course->quiz->passing_score }}% or higher to unlock your certificate.</p>
        </div>
    </section>

    <section class="bg-white py-12 dark:bg-slate-950">
        <div class="mx-auto max-w-3xl px-6">
            @if($result)
                <div class="rounded-2xl border p-8 text-center {{ $result->passed ? 'border-emerald-200 bg-emerald-50 dark:border-emerald-900 dark:bg-emerald-500/10' : 'border-red-200 bg-red-50 dark:border-red-900 dark:bg-red-500/10' }}">
                    <p class="text-4xl font-bold {{ $result->passed ? 'text-emerald-700 dark:text-emerald-400' : 'text-red-700 dark:text-red-400' }}">{{ $result->score }}%</p>
                    <p class="mt-2 font-semibold {{ $result->passed ? 'text-emerald-700 dark:text-emerald-400' : 'text-red-700 dark:text-red-400' }}">
                        {{ $result->passed ? 'You passed!' : 'Not quite - try again.' }}
                    </p>

                    @if($result->passed)
                        <a href="{{ route('elearning.certificate', $course->slug) }}" class="mt-6 inline-block rounded-full bg-slate-900 px-6 py-3 text-sm font-semibold text-white transition hover:bg-amber-600 dark:bg-amber-500 dark:text-slate-950 dark:hover:bg-amber-400">
                            View Your Certificate
                        </a>
                    @else
                        <button wire:click="retake" class="mt-6 rounded-full bg-slate-900 px-6 py-3 text-sm font-semibold text-white transition hover:bg-amber-600 dark:bg-amber-500 dark:text-slate-950 dark:hover:bg-amber-400">
                            Try Again
                        </button>
                    @endif
                </div>
            @else
                <form wire:submit="submit" class="space-y-8">
                    @foreach($course->quiz->questions as $question)
                        <div class="rounded-2xl border border-slate-200 p-6 dark:border-slate-800">
                            <p class="font-semibold text-slate-900 dark:text-white">{{ $loop->iteration }}. {{ $question->question }}</p>
                            <div class="mt-4 space-y-2">
                                @foreach($question->options as $option)
                                    <label class="flex items-center gap-3 rounded-lg border border-slate-200 px-4 py-3 text-sm transition hover:border-amber-300 dark:border-slate-800 dark:text-slate-300 dark:hover:border-amber-500/40">
                                        <input type="radio" wire:model="answers.{{ $question->id }}" value="{{ $option->id }}" class="text-amber-600 focus:ring-amber-500" />
                                        {{ $option->option }}
                                    </label>
                                @endforeach
                            </div>
                        </div>
                    @endforeach

                    <button type="submit" class="w-full rounded-full bg-slate-900 px-6 py-3 text-sm font-semibold text-white transition hover:bg-amber-600 dark:bg-amber-500 dark:text-slate-950 dark:hover:bg-amber-400">
                        Submit Quiz
                    </button>
                </form>
            @endif
        </div>
    </section>
</div>
