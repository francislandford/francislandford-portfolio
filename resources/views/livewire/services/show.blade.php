<div>
    <section class="bg-slate-950 py-20 text-center">
        <div class="mx-auto max-w-3xl px-6">
            <a href="{{ route('services.index') }}" class="font-mono text-sm font-semibold text-amber-400 hover:text-amber-300">&larr; All Services</a>
            <h1 class="mt-4 text-4xl font-bold text-white sm:text-5xl">{{ $service->title }}</h1>
            <p class="mt-4 text-lg text-slate-300">{{ $service->summary }}</p>
        </div>
    </section>

    <section class="bg-white py-20 dark:bg-slate-950">
        <div class="mx-auto max-w-3xl px-6">
            @if($service->body)
                <div class="prose prose-slate max-w-none dark:prose-invert">
                    {!! nl2br(e($service->body)) !!}
                </div>
            @else
                <p class="text-slate-500 dark:text-slate-400">More details on this service are coming soon.</p>
            @endif

            <a href="{{ route('contact') }}" class="mt-10 inline-block rounded-full bg-slate-900 px-7 py-3 text-sm font-semibold text-white transition hover:-translate-y-0.5 hover:bg-amber-600 hover:shadow-lg hover:shadow-amber-600/20 dark:bg-amber-500 dark:text-slate-950 dark:hover:bg-amber-400">
                Discuss Your Project
            </a>
        </div>
    </section>

    @if($otherServices->isNotEmpty())
        <section class="bg-slate-50 py-20 dark:bg-slate-900/40">
            <div class="mx-auto max-w-6xl px-6">
                <p class="font-mono text-xs font-semibold uppercase tracking-[0.2em] text-amber-600 dark:text-amber-400">More</p>
                <h2 class="mt-2 text-2xl font-bold text-slate-900 dark:text-white">Other Services</h2>
                <div class="mt-8 grid gap-6 sm:grid-cols-3">
                    @foreach($otherServices as $other)
                        <a href="{{ route('services.show', $other->slug) }}" class="group rounded-2xl border border-slate-200 bg-white p-6 transition hover:-translate-y-1 hover:border-amber-300 hover:shadow-lg hover:shadow-slate-900/5 dark:border-slate-800 dark:bg-slate-950 dark:hover:border-amber-500/40 dark:hover:shadow-black/30">
                            <h3 class="font-semibold text-slate-900 group-hover:text-amber-600 dark:text-white dark:group-hover:text-amber-400">{{ $other->title }}</h3>
                            <p class="mt-2 text-sm text-slate-500 dark:text-slate-400">{{ $other->summary }}</p>
                        </a>
                    @endforeach
                </div>
            </div>
        </section>
    @endif
</div>
