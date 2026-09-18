<div>
    <section class="bg-slate-950 py-16">
        <div class="mx-auto max-w-3xl px-6">
            <p class="font-mono text-xs font-semibold uppercase tracking-[0.25em] text-amber-400">Search</p>
            <h1 class="mt-3 text-3xl font-bold text-white sm:text-4xl">Search Projects &amp; Blog</h1>

            <div class="mt-6">
                <input
                    type="search"
                    wire:model.live.debounce.400ms="query"
                    placeholder="Search for a technology, project, or topic…"
                    autofocus
                    class="w-full rounded-full border-0 bg-white px-6 py-3.5 text-sm text-slate-900 shadow-lg focus:outline-none focus:ring-2 focus:ring-amber-500"
                />
            </div>

            <div class="mt-4 flex gap-2">
                <button wire:click="setType(null)" class="rounded-full px-4 py-1.5 font-mono text-xs font-semibold {{ !$type ? 'bg-amber-500 text-slate-950' : 'bg-slate-800 text-slate-300 hover:bg-slate-700' }}">
                    All
                </button>
                <button wire:click="setType('projects')" class="rounded-full px-4 py-1.5 font-mono text-xs font-semibold {{ $type === 'projects' ? 'bg-amber-500 text-slate-950' : 'bg-slate-800 text-slate-300 hover:bg-slate-700' }}">
                    Projects
                </button>
                <button wire:click="setType('posts')" class="rounded-full px-4 py-1.5 font-mono text-xs font-semibold {{ $type === 'posts' ? 'bg-amber-500 text-slate-950' : 'bg-slate-800 text-slate-300 hover:bg-slate-700' }}">
                    Blog
                </button>
            </div>
        </div>
    </section>

    <section class="bg-white py-16 dark:bg-slate-950">
        <div class="mx-auto max-w-3xl px-6">
            @if(blank($query))
                <p class="text-sm text-slate-500 dark:text-slate-400">Start typing to search across projects and blog posts.</p>
            @elseif($results->isEmpty())
                <div class="rounded-2xl border border-dashed border-slate-300 py-16 text-center dark:border-slate-800">
                    <p class="text-slate-500 dark:text-slate-400">No results for "{{ $query }}".</p>
                    <a href="{{ route('contact') }}" class="mt-3 inline-block text-sm font-semibold text-amber-600 hover:text-amber-700 dark:text-amber-400 dark:hover:text-amber-300">
                        Can't find what you're looking for? Get in touch →
                    </a>
                </div>
            @else
                <p class="font-mono text-sm text-slate-500 dark:text-slate-400">{{ $results->count() }} result{{ $results->count() === 1 ? '' : 's' }} for "{{ $query }}"</p>

                <div class="mt-6 space-y-4">
                    @foreach($results as $result)
                        <a href="{{ $result['url'] }}" class="group block rounded-2xl border border-slate-200 p-5 transition hover:-translate-y-0.5 hover:border-amber-300 hover:shadow-lg hover:shadow-slate-900/5 dark:border-slate-800 dark:hover:border-amber-500/40 dark:hover:shadow-black/30">
                            <span class="rounded-full px-2.5 py-1 font-mono text-xs font-semibold {{ $result['type'] === 'Project' ? 'bg-amber-100 text-amber-700 dark:bg-amber-500/10 dark:text-amber-400' : 'bg-blue-100 text-blue-700 dark:bg-blue-500/10 dark:text-blue-400' }}">
                                {{ $result['type'] }}
                            </span>
                            <h2 class="mt-3 text-lg font-semibold text-slate-900 group-hover:text-amber-600 dark:text-white dark:group-hover:text-amber-400">{{ $result['title'] }}</h2>
                            <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">{{ $result['excerpt'] }}</p>
                        </a>
                    @endforeach
                </div>
            @endif
        </div>
    </section>
</div>
