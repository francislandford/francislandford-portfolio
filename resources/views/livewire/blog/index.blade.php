<div>
    <section class="bg-slate-950 py-20 text-center">
        <div class="mx-auto max-w-3xl px-6">
            <p class="font-mono text-xs font-semibold uppercase tracking-[0.25em] text-amber-400">Blog</p>
            <h1 class="mt-4 text-4xl font-bold text-white sm:text-5xl">Writing &amp; Notes</h1>
            <p class="mt-4 text-lg text-slate-300">Thoughts on software, projects, and what I'm learning.</p>
        </div>
    </section>

    <section class="bg-white py-20 dark:bg-slate-950">
        <div class="mx-auto max-w-4xl px-6">
            @if($posts->isEmpty())
                <div class="rounded-2xl border border-dashed border-slate-300 py-20 text-center dark:border-slate-800">
                    <p class="text-slate-500 dark:text-slate-400">No posts published yet — check back soon.</p>
                </div>
            @else
                <div class="space-y-6">
                    @foreach($posts as $post)
                        <a href="{{ route('blog.show', $post->slug) }}" class="group block rounded-2xl border border-slate-200 p-6 transition hover:-translate-y-1 hover:border-amber-300 hover:shadow-lg hover:shadow-slate-900/5 dark:border-slate-800 dark:hover:border-amber-500/40 dark:hover:shadow-black/30 sm:flex sm:gap-6">
                            @if($post->cover_image)
                                <img src="{{ Storage::disk('public')->url($post->cover_image) }}" alt="{{ $post->title }}" class="h-40 w-full rounded-xl object-cover sm:w-48 sm:flex-none" />
                            @endif
                            <div class="mt-4 sm:mt-0">
                                <p class="font-mono text-xs font-semibold uppercase tracking-wide text-amber-600 dark:text-amber-400">{{ $post->published_at?->format('M j, Y') }}</p>
                                <h2 class="mt-1 text-xl font-semibold text-slate-900 group-hover:text-amber-600 dark:text-white dark:group-hover:text-amber-400">{{ $post->title }}</h2>
                                <p class="mt-2 line-clamp-2 text-sm text-slate-500 dark:text-slate-400">{{ $post->excerpt ?? $post->ai_summary }}</p>
                            </div>
                        </a>
                    @endforeach
                </div>

                <div class="mt-12">
                    {{ $posts->links() }}
                </div>
            @endif
        </div>
    </section>
</div>
