<div>
    <section class="bg-slate-950 py-20 text-center">
        <div class="mx-auto max-w-3xl px-6">
            <p class="font-mono text-xs font-semibold uppercase tracking-[0.25em] text-amber-400">Services</p>
            <h1 class="mt-4 text-4xl font-bold text-white sm:text-5xl">What I Do</h1>
            <p class="mt-4 text-lg text-slate-300">Tailored digital solutions to help you achieve your goals.</p>
        </div>
    </section>

    <section class="bg-white py-20 dark:bg-slate-950">
        <div class="mx-auto max-w-6xl px-6">
            <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
                @foreach($services as $service)
                    <a href="{{ route('services.show', $service->slug) }}" class="group rounded-2xl border border-slate-200 p-6 transition hover:-translate-y-1 hover:border-amber-300 hover:shadow-lg hover:shadow-slate-900/5 dark:border-slate-800 dark:hover:border-amber-500/40 dark:hover:shadow-black/30">
                        <span class="flex h-11 w-11 items-center justify-center rounded-xl border border-slate-200 bg-slate-50 font-mono text-sm font-semibold text-slate-700 dark:border-slate-800 dark:bg-slate-900 dark:text-slate-300">
                            {{ Illuminate\Support\Str::substr($service->title, 0, 1) }}
                        </span>
                        <h2 class="mt-4 text-lg font-semibold text-slate-900 group-hover:text-amber-600 dark:text-white dark:group-hover:text-amber-400">{{ $service->title }}</h2>
                        <p class="mt-2 text-sm leading-relaxed text-slate-500 dark:text-slate-400">{{ $service->summary }}</p>
                        <span class="mt-4 inline-flex items-center gap-1 text-sm font-semibold text-amber-600 transition group-hover:gap-2 dark:text-amber-400">
                            Learn more
                            <svg class="h-3.5 w-3.5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M3 10a.75.75 0 01.75-.75h10.638L10.23 5.29a.75.75 0 111.04-1.08l5.5 5.25a.75.75 0 010 1.08l-5.5 5.25a.75.75 0 11-1.04-1.08l4.158-3.96H3.75A.75.75 0 013 10z" clip-rule="evenodd" /></svg>
                        </span>
                    </a>
                @endforeach
            </div>
        </div>
    </section>
</div>
