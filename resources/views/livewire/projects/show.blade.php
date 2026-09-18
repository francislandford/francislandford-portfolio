<div>
    <section class="bg-slate-950 py-20">
        <div class="mx-auto max-w-4xl px-6 text-center">
            <a href="{{ route('projects.index') }}" class="font-mono text-sm font-semibold text-amber-400 hover:text-amber-300">&larr; All Projects</a>
            <p class="mt-4 font-mono text-xs font-semibold uppercase tracking-[0.25em] text-amber-400">{{ $project->client }}</p>
            <h1 class="mt-2 text-4xl font-bold text-white sm:text-5xl">{{ $project->title }}</h1>

            @if($project->categories->isNotEmpty())
                <div class="mt-5 flex flex-wrap justify-center gap-2">
                    @foreach($project->categories as $cat)
                        <span class="rounded-full border border-slate-700 px-3 py-1 text-xs text-slate-300">{{ $cat->name }}</span>
                    @endforeach
                </div>
            @endif
        </div>
    </section>

    @if($project->cover_image)
        <div class="mx-auto -mt-10 max-w-5xl px-6">
            <img src="{{ Storage::url($project->cover_image) }}" alt="{{ $project->title }}" class="w-full rounded-2xl shadow-xl shadow-slate-900/20 dark:shadow-black/40" />
        </div>
    @endif

    <section class="bg-white py-16 dark:bg-slate-950">
        <div class="mx-auto max-w-3xl px-6">
            <div class="grid gap-8 border-b border-slate-100 pb-10 dark:border-slate-900 sm:grid-cols-3">
                <div>
                    <p class="font-mono text-xs font-semibold uppercase tracking-wide text-slate-400 dark:text-slate-500">Client</p>
                    <p class="mt-1 font-medium text-slate-900 dark:text-white">{{ $project->client ?: '—' }}</p>
                </div>
                <div>
                    <p class="font-mono text-xs font-semibold uppercase tracking-wide text-slate-400 dark:text-slate-500">Start Date</p>
                    <p class="mt-1 font-medium text-slate-900 dark:text-white">{{ $project->start_date?->format('M Y') ?: '—' }}</p>
                </div>
                <div>
                    <p class="font-mono text-xs font-semibold uppercase tracking-wide text-slate-400 dark:text-slate-500">Live Site</p>
                    @if($project->live_url)
                        <a href="{{ $project->live_url }}" target="_blank" rel="noopener noreferrer" class="mt-1 inline-flex items-center gap-1 font-medium text-amber-600 hover:text-amber-700 dark:text-amber-400 dark:hover:text-amber-300">
                            Visit
                            <svg class="h-3.5 w-3.5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M5.22 14.78a.75.75 0 001.06 0l7.22-7.22v5.69a.75.75 0 001.5 0v-7.5a.75.75 0 00-.75-.75h-7.5a.75.75 0 000 1.5h5.69l-7.22 7.22a.75.75 0 000 1.06z" clip-rule="evenodd" /></svg>
                        </a>
                    @else
                        <p class="mt-1 font-medium text-slate-900 dark:text-white">—</p>
                    @endif
                </div>
            </div>

            <div class="prose prose-slate mt-10 max-w-none dark:prose-invert">
                <p class="font-medium text-slate-700 dark:text-slate-300">{{ $project->description }}</p>
                @if($project->body)
                    <div class="mt-4">{!! nl2br(e($project->body)) !!}</div>
                @endif
            </div>

            @if($project->skills->isNotEmpty())
                <div class="mt-10">
                    <p class="font-mono text-xs font-semibold uppercase tracking-wide text-slate-400 dark:text-slate-500">Tech Used</p>
                    <div class="mt-3 flex flex-wrap gap-2">
                        @foreach($project->skills as $skill)
                            <span class="rounded-full border border-slate-200 px-3 py-1 text-sm text-slate-700 dark:border-slate-800 dark:text-slate-300">{{ $skill->name }}</span>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    </section>

    @if($project->testimonials->isNotEmpty())
        <section class="bg-slate-50 py-16 dark:bg-slate-900/40">
            <div class="mx-auto max-w-3xl px-6">
                @foreach($project->testimonials as $testimonial)
                    <blockquote class="rounded-2xl border border-slate-200 bg-white p-6 dark:border-slate-800 dark:bg-slate-950">
                        <p class="text-sm leading-relaxed text-slate-600 dark:text-slate-300">&ldquo;{{ $testimonial->content }}&rdquo;</p>
                        <footer class="mt-4 text-sm font-semibold text-slate-900 dark:text-white">{{ $testimonial->client_name }}</footer>
                    </blockquote>
                @endforeach
            </div>
        </section>
    @endif

    <section class="bg-white py-16 text-center dark:bg-slate-950">
        <a href="{{ route('contact') }}" class="inline-block rounded-full bg-slate-900 px-7 py-3 text-sm font-semibold text-white transition hover:-translate-y-0.5 hover:bg-amber-600 hover:shadow-lg hover:shadow-amber-600/20 dark:bg-amber-500 dark:text-slate-950 dark:hover:bg-amber-400">
            Start a Similar Project
        </a>
    </section>
</div>
