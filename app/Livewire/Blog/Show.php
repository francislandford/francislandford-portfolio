<?php

namespace App\Livewire\Blog;

use App\Models\Post;
use Illuminate\View\View;
use Livewire\Component;

class Show extends Component
{
    public Post $post;

    public function mount(Post $post): void
    {
        abort_unless($post->status === 'published' && $post->published_at <= now(), 404);

        $post->incrementQuietly('views_count');

        $this->post = $post->load(['author', 'categories', 'tags']);
    }

    public function render(): View
    {
        $image = $this->post->og_image
            ? \Storage::url($this->post->og_image)
            : ($this->post->cover_image ? \Storage::url($this->post->cover_image) : null);

        $articleSchema = array_filter([
            '@context' => 'https://schema.org',
            '@type' => 'Article',
            'headline' => $this->post->title,
            'description' => $this->post->excerpt ?: $this->post->ai_summary,
            'datePublished' => $this->post->published_at?->toIso8601String(),
            'dateModified' => $this->post->updated_at?->toIso8601String(),
            'image' => $image ? [url($image)] : null,
            'author' => $this->post->author ? [
                '@type' => 'Person',
                'name' => $this->post->author->name,
            ] : null,
            'mainEntityOfPage' => [
                '@type' => 'WebPage',
                '@id' => url()->current(),
            ],
        ]);

        return view('livewire.blog.show', [
            'relatedPosts' => Post::query()
                ->where('status', 'published')
                ->where('id', '!=', $this->post->id)
                ->whereHas('categories', fn ($q) => $q->whereIn('categories.id', $this->post->categories->pluck('id')))
                ->limit(3)
                ->get(),
        ])->layout('components.layouts.app', [
            'title' => $this->post->meta_title ?: $this->post->title,
            'description' => $this->post->meta_description ?: ($this->post->excerpt ?: $this->post->ai_summary),
            'ogImage' => $image,
            'ogType' => 'article',
            'structuredData' => [$articleSchema],
        ]);
    }
}
