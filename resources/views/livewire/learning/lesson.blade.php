<div>
    <section class="border-b border-slate-200 bg-slate-50 py-10 dark:border-slate-800 dark:bg-slate-900/40">
        <div class="mx-auto max-w-3xl px-6">
            <a href="{{ route('elearning.show', $course->slug) }}" class="font-mono text-sm font-semibold text-amber-600 hover:text-amber-700 dark:text-amber-400 dark:hover:text-amber-300">&larr; {{ $course->title }}</a>
            <div class="mt-3 flex items-center gap-3">
                <span @class([
                    'rounded-full px-2.5 py-0.5 font-mono text-[11px] font-semibold uppercase tracking-wide',
                    'bg-blue-100 text-blue-700 dark:bg-blue-500/10 dark:text-blue-400' => $lesson->type === 'video',
                    'bg-amber-100 text-amber-700 dark:bg-amber-500/10 dark:text-amber-400' => $lesson->type === 'code',
                    'bg-slate-100 text-slate-600 dark:bg-slate-800 dark:text-slate-400' => $lesson->type === 'text',
                ])>
                    {{ $lesson->type }}
                </span>
            </div>
            <h1 class="mt-3 text-3xl font-bold text-slate-900 dark:text-white">{{ $lesson->title }}</h1>
        </div>
    </section>

    <section class="bg-white py-12 dark:bg-slate-950">
        <div class="mx-auto max-w-3xl px-6">
            @if($lesson->video_url)
                <div class="mb-8 aspect-video overflow-hidden rounded-2xl bg-slate-900">
                    <iframe src="{{ $lesson->video_url }}" class="h-full w-full" allowfullscreen></iframe>
                </div>
            @endif

            <div class="prose prose-slate max-w-none dark:prose-invert">
                {!! $lesson->bodyHtml() !!}
            </div>

            @if($lesson->attachments->isNotEmpty())
                <div class="mt-10 rounded-2xl border border-slate-200 p-6 dark:border-slate-800">
                    <p class="font-mono text-xs font-semibold uppercase tracking-[0.2em] text-amber-600 dark:text-amber-400">Resources</p>
                    <ul class="mt-4 space-y-2">
                        @foreach($lesson->attachments as $attachment)
                            <li>
                                <a href="{{ Storage::url($attachment->file) }}" target="_blank" rel="noopener noreferrer" class="inline-flex items-center gap-2 text-sm font-medium text-slate-700 hover:text-amber-600 dark:text-slate-300 dark:hover:text-amber-400">
                                    <svg class="h-4 w-4 text-slate-400 dark:text-slate-500" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 3a.75.75 0 01.75.75v10.638l3.96-4.158a.75.75 0 111.08 1.04l-5.25 5.5a.75.75 0 01-1.08 0l-5.25-5.5a.75.75 0 111.08-1.04l3.96 4.158V3.75A.75.75 0 0110 3zM3.5 12a.75.75 0 01.75.75v2.75a1 1 0 001 1h9.5a1 1 0 001-1v-2.75a.75.75 0 011.5 0v2.75a2.5 2.5 0 01-2.5 2.5h-9.5A2.5 2.5 0 012.75 15.5v-2.75A.75.75 0 013.5 12z" clip-rule="evenodd" /></svg>
                                    {{ $attachment->title }}
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @if($enrollment)
                <div class="mt-10 flex items-center gap-4">
                    @if($isComplete)
                        <span class="inline-flex items-center gap-2 rounded-full bg-emerald-100 px-4 py-2 text-sm font-semibold text-emerald-700 dark:bg-emerald-500/10 dark:text-emerald-400">
                            <svg class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M16.704 4.153a.75.75 0 01.143 1.052l-8 10.5a.75.75 0 01-1.127.075l-4.5-4.5a.75.75 0 011.06-1.06l3.894 3.893 7.48-9.817a.75.75 0 011.05-.143z" clip-rule="evenodd" /></svg>
                            Completed
                        </span>
                    @else
                        <button wire:click="markComplete" class="rounded-full bg-slate-900 px-6 py-2.5 text-sm font-semibold text-white transition hover:bg-amber-600 dark:bg-amber-500 dark:text-slate-950 dark:hover:bg-amber-400">
                            Mark as Complete
                        </button>
                    @endif
                </div>
            @endif

            <div class="mt-10 flex items-center justify-between border-t border-slate-200 pt-6 dark:border-slate-800">
                @if($previousLesson)
                    <a href="{{ route('elearning.lesson', [$course->slug, $previousLesson->slug]) }}" class="text-sm font-semibold text-slate-600 hover:text-amber-600 dark:text-slate-400 dark:hover:text-amber-400">&larr; {{ $previousLesson->title }}</a>
                @else
                    <span></span>
                @endif

                @if($nextLesson)
                    <a href="{{ route('elearning.lesson', [$course->slug, $nextLesson->slug]) }}" class="text-sm font-semibold text-slate-600 hover:text-amber-600 dark:text-slate-400 dark:hover:text-amber-400">{{ $nextLesson->title }} &rarr;</a>
                @elseif($enrollment && $course->quiz)
                    <a href="{{ route('elearning.quiz', $course->slug) }}" class="text-sm font-semibold text-amber-600 hover:text-amber-700 dark:text-amber-400 dark:hover:text-amber-300">Take the Final Quiz &rarr;</a>
                @endif
            </div>
        </div>
    </section>
</div>
