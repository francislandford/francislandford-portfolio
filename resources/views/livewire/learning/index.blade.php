<div>
    <section class="bg-slate-950 py-20 text-center">
        <div class="mx-auto max-w-3xl px-6">
            <p class="font-mono text-xs font-semibold uppercase tracking-[0.25em] text-amber-400">E-Learning</p>
            <h1 class="mt-4 text-4xl font-bold text-white sm:text-5xl">Courses &amp; Tutorials</h1>
            <p class="mt-4 text-lg text-slate-300">Learn what I've learned building software &mdash; some courses are free, others go deeper.</p>
        </div>
    </section>

    <section class="bg-white py-20 dark:bg-slate-950">
        <div class="mx-auto max-w-6xl px-6">
            @if($courses->isEmpty())
                <div class="rounded-2xl border border-dashed border-slate-300 py-20 text-center dark:border-slate-800">
                    <p class="text-slate-500 dark:text-slate-400">No courses published yet &mdash; check back soon.</p>
                </div>
            @else
                <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
                    @foreach($courses as $course)
                        <a href="{{ route('elearning.show', $course->slug) }}" class="group overflow-hidden rounded-2xl border border-slate-200 bg-white transition hover:-translate-y-1 hover:border-amber-300 hover:shadow-lg hover:shadow-slate-900/5 dark:border-slate-800 dark:bg-slate-900 dark:hover:border-amber-500/40 dark:hover:shadow-black/30">
                            @if($course->cover_image)
                                <img src="{{ Storage::disk('public')->url($course->cover_image) }}" alt="{{ $course->title }}" class="h-44 w-full object-cover" />
                            @else
                                <div class="flex h-44 w-full items-center justify-center bg-slate-900 text-white dark:bg-slate-950">
                                    <span class="font-mono text-sm font-semibold uppercase tracking-widest">{{ $course->level ?? 'Course' }}</span>
                                </div>
                            @endif
                            <div class="p-6">
                                <div class="flex items-center justify-between">
                                    <span class="rounded-full px-3 py-1 font-mono text-xs font-semibold {{ $course->isFree() ? 'bg-emerald-100 text-emerald-700 dark:bg-emerald-500/10 dark:text-emerald-400' : 'bg-amber-100 text-amber-700 dark:bg-amber-500/10 dark:text-amber-400' }}">
                                        {{ $course->isFree() ? 'Free' : number_format($course->price, 2).' '.$course->currency }}
                                    </span>
                                    @if($course->level)
                                        <span class="font-mono text-xs text-slate-400 dark:text-slate-500">{{ $course->level }}</span>
                                    @endif
                                </div>
                                <h2 class="mt-3 text-lg font-semibold text-slate-900 group-hover:text-amber-600 dark:text-white dark:group-hover:text-amber-400">{{ $course->title }}</h2>
                                <p class="mt-2 line-clamp-2 text-sm text-slate-500 dark:text-slate-400">{{ $course->excerpt ?? $course->description }}</p>
                            </div>
                        </a>
                    @endforeach
                </div>
            @endif
        </div>
    </section>
</div>
