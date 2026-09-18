<div>
    <section class="bg-slate-950 py-20 text-center">
        <div class="mx-auto max-w-3xl px-6">
            <p class="font-mono text-xs font-semibold uppercase tracking-[0.25em] text-amber-400">Portfolio</p>
            <h1 class="mt-4 text-4xl font-bold text-white sm:text-5xl">Projects</h1>
            <p class="mt-4 text-lg text-slate-300">A look at what I've been building.</p>
        </div>
    </section>

    <section class="bg-white py-20 dark:bg-slate-950">
        <div class="mx-auto max-w-6xl px-6">
            @if($categories->isNotEmpty())
                <div class="flex flex-wrap gap-2">
                    <button wire:click="selectCategory(null)" class="rounded-full px-4 py-2 text-sm font-medium transition {{ !$category ? 'bg-slate-900 text-white dark:bg-amber-500 dark:text-slate-950' : 'border border-slate-200 text-slate-600 hover:border-amber-300 hover:text-amber-600 dark:border-slate-800 dark:text-slate-400 dark:hover:border-amber-500/40 dark:hover:text-amber-400' }}">
                        All
                    </button>
                    @foreach($categories as $cat)
                        <button wire:click="selectCategory('{{ $cat->slug }}')" class="rounded-full px-4 py-2 text-sm font-medium transition {{ $category === $cat->slug ? 'bg-slate-900 text-white dark:bg-amber-500 dark:text-slate-950' : 'border border-slate-200 text-slate-600 hover:border-amber-300 hover:text-amber-600 dark:border-slate-800 dark:text-slate-400 dark:hover:border-amber-500/40 dark:hover:text-amber-400' }}">
                            {{ $cat->name }}
                        </button>
                    @endforeach
                </div>
            @endif

            @if($projects->isEmpty())
                <p class="mt-12 text-sm text-slate-500 dark:text-slate-400">No projects found in this category yet.</p>
            @else
                <div class="mt-10 grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
                    @foreach($projects as $project)
                        <a href="{{ route('projects.show', $project->slug) }}" class="group overflow-hidden rounded-2xl border border-slate-200 bg-white transition hover:-translate-y-1 hover:border-amber-300 hover:shadow-lg hover:shadow-slate-900/5 dark:border-slate-800 dark:bg-slate-900 dark:hover:border-amber-500/40 dark:hover:shadow-black/30">
                            @if($project->cover_image)
                                <img src="{{ Storage::url($project->cover_image) }}" alt="{{ $project->title }}" class="h-44 w-full object-cover" />
                            @else
                                <div class="flex h-44 w-full items-center justify-center bg-slate-900 text-white dark:bg-slate-950">
                                    <span class="font-mono text-sm font-semibold uppercase tracking-widest">{{ $project->client }}</span>
                                </div>
                            @endif
                            <div class="p-6">
                                <div class="flex items-center justify-between gap-2">
                                    <p class="font-mono text-xs font-semibold uppercase tracking-wide text-amber-600 dark:text-amber-400">{{ $project->client }}</p>
                                    @if($project->is_featured)
                                        <span class="rounded-full border border-amber-200 bg-amber-50 px-2 py-0.5 font-mono text-[10px] font-semibold uppercase tracking-wide text-amber-700 dark:border-amber-500/30 dark:bg-amber-500/10 dark:text-amber-400">Featured</span>
                                    @endif
                                </div>
                                <h2 class="mt-1 text-lg font-semibold text-slate-900 group-hover:text-amber-600 dark:text-white dark:group-hover:text-amber-400">{{ $project->title }}</h2>
                                <p class="mt-2 line-clamp-2 text-sm text-slate-500 dark:text-slate-400">{{ $project->description }}</p>
                            </div>
                        </a>
                    @endforeach
                </div>

                <div class="mt-12">
                    {{ $projects->links() }}
                </div>
            @endif
        </div>
    </section>
</div>
