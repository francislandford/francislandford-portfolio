<div>
    <section class="border-b border-slate-200 bg-slate-50 py-12 dark:border-slate-800 dark:bg-slate-900/40">
        <div class="mx-auto max-w-5xl px-6">
            <h1 class="text-3xl font-bold text-slate-900 dark:text-white">My Courses</h1>
            <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">Welcome back, {{ auth()->user()->name }}.</p>
        </div>
    </section>

    <section class="bg-white py-12 dark:bg-slate-950">
        <div class="mx-auto max-w-5xl px-6">
            @if($enrollments->isEmpty())
                <div class="rounded-2xl border border-dashed border-slate-300 py-16 text-center dark:border-slate-800">
                    <p class="text-slate-500 dark:text-slate-400">You're not enrolled in any courses yet.</p>
                    <a href="{{ route('elearning') }}" class="mt-4 inline-block text-sm font-semibold text-amber-600 hover:text-amber-700 dark:text-amber-400 dark:hover:text-amber-300">Browse courses →</a>
                </div>
            @else
                <div class="space-y-4">
                    @foreach($enrollments as $enrollment)
                        <div class="flex items-center justify-between rounded-2xl border border-slate-200 p-6 dark:border-slate-800">
                            <div>
                                <p class="font-mono text-xs font-semibold uppercase tracking-wide text-amber-600 dark:text-amber-400">
                                    {{ $enrollment->status === 'completed' ? 'Completed' : 'In Progress' }}
                                </p>
                                <h2 class="mt-1 text-lg font-semibold text-slate-900 dark:text-white">{{ $enrollment->course->title }}</h2>
                                <div class="mt-3 h-2 w-64 overflow-hidden rounded-full bg-slate-100 dark:bg-slate-800">
                                    <div class="h-full bg-amber-500" style="width: {{ $enrollment->progressPercentage() }}%"></div>
                                </div>
                                <p class="mt-1 font-mono text-xs text-slate-400 dark:text-slate-500">{{ $enrollment->progressPercentage() }}% complete</p>
                            </div>
                            <div class="flex flex-col gap-2 text-right">
                                <a href="{{ route('elearning.show', $enrollment->course->slug) }}" class="text-sm font-semibold text-amber-600 hover:text-amber-700 dark:text-amber-400 dark:hover:text-amber-300">
                                    Continue →
                                </a>
                                @if($enrollment->isEligibleForCertificate())
                                    <a href="{{ route('elearning.certificate', $enrollment->course->slug) }}" class="text-sm text-slate-500 hover:text-slate-700 dark:text-slate-400 dark:hover:text-slate-200">
                                        View Certificate
                                    </a>
                                @elseif($enrollment->course->quiz && $enrollment->hasCompletedAllLessons())
                                    <a href="{{ route('elearning.quiz', $enrollment->course->slug) }}" class="text-sm font-semibold text-amber-600 hover:text-amber-700 dark:text-amber-400 dark:hover:text-amber-300">
                                        Take the Quiz →
                                    </a>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </section>
</div>
