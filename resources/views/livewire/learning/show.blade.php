<div>
    <section class="bg-slate-950 py-20 text-center">
        <div class="mx-auto max-w-3xl px-6">
            <a href="{{ route('elearning') }}" class="font-mono text-sm font-semibold text-amber-400 hover:text-amber-300">&larr; All Courses</a>
            <p class="mt-4 font-mono text-xs font-semibold uppercase tracking-[0.25em] text-amber-400">{{ $course->level ?? 'Course' }}</p>
            <h1 class="mt-2 text-4xl font-bold text-white sm:text-5xl">{{ $course->title }}</h1>
            <p class="mt-4 text-lg text-slate-300">{{ $course->excerpt ?? $course->description }}</p>
        </div>
    </section>

    @if($course->cover_image)
        <div class="mx-auto -mt-10 max-w-4xl px-6">
            <img src="{{ Storage::disk('public')->url($course->cover_image) }}" alt="{{ $course->title }}" class="w-full rounded-2xl shadow-xl shadow-slate-900/20 dark:shadow-black/40" />
        </div>
    @endif

    <section class="bg-white py-16 dark:bg-slate-950">
        <div class="mx-auto grid max-w-5xl gap-10 px-6 sm:grid-cols-5">
            <div class="sm:col-span-3">
                <div class="prose prose-slate max-w-none dark:prose-invert">
                    <div>{!! nl2br(e($course->description)) !!}</div>
                    @if($course->body)
                        <div class="mt-4">{!! nl2br(e($course->body)) !!}</div>
                    @endif
                </div>

                <h2 class="mt-10 text-xl font-bold text-slate-900 dark:text-white">Lessons</h2>
                <div class="mt-4 divide-y divide-slate-200 rounded-2xl border border-slate-200 dark:divide-slate-800 dark:border-slate-800">
                    @forelse($course->lessons as $lesson)
                        @php
                            $unlocked = $lesson->is_free_preview || $enrollment;
                        @endphp
                        <div class="flex items-center justify-between px-5 py-4">
                            <div class="flex items-center gap-3">
                                @if($unlocked)
                                    <svg class="h-4 w-4 text-emerald-500" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M16.704 4.153a.75.75 0 01.143 1.052l-8 10.5a.75.75 0 01-1.127.075l-4.5-4.5a.75.75 0 011.06-1.06l3.894 3.893 7.48-9.817a.75.75 0 011.05-.143z" clip-rule="evenodd" /></svg>
                                @else
                                    <svg class="h-4 w-4 text-slate-300 dark:text-slate-700" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 1a4.5 4.5 0 00-4.5 4.5V9H5a2 2 0 00-2 2v6a2 2 0 002 2h10a2 2 0 002-2v-6a2 2 0 00-2-2h-.5V5.5A4.5 4.5 0 0010 1zm3 8V5.5a3 3 0 10-6 0V9h6z" clip-rule="evenodd" /></svg>
                                @endif
                                <span class="text-sm font-medium {{ $unlocked ? 'text-slate-900 dark:text-white' : 'text-slate-400 dark:text-slate-600' }}">{{ $lesson->title }}</span>
                                <span @class([
                                    'rounded-full px-2 py-0.5 font-mono text-[10px] font-semibold uppercase tracking-wide',
                                    'bg-blue-100 text-blue-700 dark:bg-blue-500/10 dark:text-blue-400' => $lesson->type === 'video',
                                    'bg-amber-100 text-amber-700 dark:bg-amber-500/10 dark:text-amber-400' => $lesson->type === 'code',
                                    'bg-slate-100 text-slate-500 dark:bg-slate-800 dark:text-slate-400' => $lesson->type === 'text',
                                ])>
                                    {{ $lesson->type }}
                                </span>
                                @if($lesson->is_free_preview)
                                    <span class="rounded-full bg-emerald-100 px-2 py-0.5 font-mono text-xs font-semibold text-emerald-700 dark:bg-emerald-500/10 dark:text-emerald-400">Preview</span>
                                @endif
                            </div>
                            @if($unlocked)
                                <a href="{{ route('elearning.lesson', [$course->slug, $lesson->slug]) }}" class="text-sm font-semibold text-amber-600 hover:text-amber-700 dark:text-amber-400 dark:hover:text-amber-300">View →</a>
                            @endif
                        </div>
                    @empty
                        <p class="px-5 py-4 text-sm text-slate-500 dark:text-slate-400">Lessons coming soon.</p>
                    @endforelse
                </div>
            </div>

            <div class="sm:col-span-2">
                <div class="rounded-2xl border border-slate-200 p-6 dark:border-slate-800">
                    <p class="font-mono text-3xl font-bold text-slate-900 dark:text-white">
                        {{ $course->isFree() ? 'Free' : number_format($course->price, 2).' '.$course->currency }}
                    </p>

                    @auth
                        @if($enrollment)
                            <div class="mt-4 rounded-lg bg-emerald-50 px-4 py-3 text-sm font-medium text-emerald-700 dark:bg-emerald-500/10 dark:text-emerald-400">
                                You're enrolled &mdash; {{ $enrollment->progressPercentage() }}% complete
                            </div>
                            <a href="{{ route('elearning.dashboard') }}" class="mt-4 block rounded-full bg-slate-900 px-6 py-3 text-center text-sm font-semibold text-white transition hover:bg-amber-600 dark:bg-amber-500 dark:text-slate-950 dark:hover:bg-amber-400">
                                Go to My Courses
                            </a>
                        @elseif($course->isFree())
                            <button wire:click="enrollFree" class="mt-4 w-full rounded-full bg-slate-900 px-6 py-3 text-sm font-semibold text-white transition hover:bg-amber-600 dark:bg-amber-500 dark:text-slate-950 dark:hover:bg-amber-400">
                                Enroll for Free
                            </button>
                        @else
                            <a href="{{ route('elearning.checkout', $course->slug) }}" class="mt-4 block rounded-full bg-slate-900 px-6 py-3 text-center text-sm font-semibold text-white transition hover:bg-amber-600 dark:bg-amber-500 dark:text-slate-950 dark:hover:bg-amber-400">
                                Enroll Now
                            </a>
                        @endif
                    @else
                        <a href="{{ route('login') }}" class="mt-4 block rounded-full bg-slate-900 px-6 py-3 text-center text-sm font-semibold text-white transition hover:bg-amber-600 dark:bg-amber-500 dark:text-slate-950 dark:hover:bg-amber-400">
                            Sign In to Enroll
                        </a>
                    @endauth

                    <ul class="mt-6 space-y-2 text-sm text-slate-500 dark:text-slate-400">
                        <li>{{ $course->lessons->count() }} lessons</li>
                        @if($course->quiz)
                            <li>Final quiz + certificate on completion</li>
                        @endif
                    </ul>
                </div>
            </div>
        </div>
    </section>
</div>
