<div>
    <section class="bg-slate-950 py-20 text-center">
        <div class="mx-auto max-w-3xl px-6">
            <a href="{{ route('blog.index') }}" class="font-mono text-sm font-semibold text-amber-400 hover:text-amber-300">&larr; All Posts</a>
            <p class="mt-4 font-mono text-xs font-semibold uppercase tracking-[0.25em] text-amber-400">
                {{ $post->published_at?->format('M j, Y') }}
                @if($post->author)
                    &middot; {{ $post->author->name }}
                @endif
            </p>
            <h1 class="mt-2 text-4xl font-bold text-white sm:text-5xl">{{ $post->title }}</h1>

            @if($post->ai_summary)
                <p class="mt-5 text-lg text-slate-300">{{ $post->ai_summary }}</p>
            @endif
        </div>
    </section>

    @if($post->cover_image)
        <div class="mx-auto -mt-10 max-w-4xl px-6">
            <img src="{{ Storage::disk('public')->url($post->cover_image) }}" alt="{{ $post->title }}" class="w-full rounded-2xl shadow-xl shadow-slate-900/20 dark:shadow-black/40" />
        </div>
    @endif

    <section class="bg-white py-16 dark:bg-slate-950">
        <div class="mx-auto max-w-3xl px-6">
            <div class="prose prose-slate max-w-none dark:prose-invert">
                {!! nl2br(e($post->body)) !!}
            </div>

            @if($post->tags->isNotEmpty())
                <div class="mt-10 flex flex-wrap gap-2">
                    @foreach($post->tags as $tag)
                        <span class="rounded-full border border-slate-200 px-3 py-1 font-mono text-xs text-slate-600 dark:border-slate-800 dark:text-slate-400">#{{ $tag->name }}</span>
                    @endforeach
                </div>
            @endif
        </div>
    </section>

    @if($relatedPosts->isNotEmpty())
        <section class="bg-slate-50 py-16 dark:bg-slate-900/40">
            <div class="mx-auto max-w-4xl px-6">
                <p class="font-mono text-xs font-semibold uppercase tracking-[0.2em] text-amber-600 dark:text-amber-400">More</p>
                <h2 class="mt-2 text-xl font-bold text-slate-900 dark:text-white">Related Posts</h2>
                <div class="mt-6 grid gap-6 sm:grid-cols-3">
                    @foreach($relatedPosts as $related)
                        <a href="{{ route('blog.show', $related->slug) }}" class="group rounded-xl border border-slate-200 bg-white p-4 transition hover:-translate-y-0.5 hover:border-amber-300 dark:border-slate-800 dark:bg-slate-950 dark:hover:border-amber-500/40">
                            <p class="text-sm font-semibold text-slate-900 group-hover:text-amber-600 dark:text-white dark:group-hover:text-amber-400">{{ $related->title }}</p>
                        </a>
                    @endforeach
                </div>
            </div>
        </section>
    @endif
</div>
