<?php

namespace App\Services\Search;

use App\Models\Post;
use App\Models\Project;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

/**
 * Relevance-ranked keyword search across projects and blog posts, using
 * MySQL full-text indexes. This is deliberately keyword-based, not
 * embeddings/vector search — it won't catch pure synonyms or paraphrases,
 * but it needs no external API, no extra cost, and no additional credential,
 * and MySQL's natural-language relevance scoring plus a LIKE-based fallback
 * (for short/stopword-only queries full-text can't score) covers the site's
 * actual content well at this scale.
 */
class SiteSearchService
{
    /**
     * @return Collection<int, array{type: string, title: string, excerpt: string, url: string, relevance: float}>
     */
    public function search(string $query, ?string $type = null, int $limit = 20): Collection
    {
        $query = trim($query);

        if ($query === '') {
            return collect();
        }

        $results = collect();

        if ($type === null || $type === 'projects') {
            $results = $results->concat($this->searchProjects($query, $limit));
        }

        if ($type === null || $type === 'posts') {
            $results = $results->concat($this->searchPosts($query, $limit));
        }

        return $results->sortByDesc('relevance')->take($limit)->values();
    }

    private function searchProjects(string $query, int $limit): Collection
    {
        $matches = Project::where('status', 'published')
            ->selectRaw('*, MATCH(title, description, body) AGAINST (? IN NATURAL LANGUAGE MODE) as relevance', [$query])
            ->whereFullText(['title', 'description', 'body'], $query)
            ->orderByDesc('relevance')
            ->limit($limit)
            ->get();

        if ($matches->isEmpty()) {
            $matches = Project::where('status', 'published')
                ->where('title', 'like', "%{$query}%")
                ->limit($limit)
                ->get()
                ->each(fn ($p) => $p->relevance = 0.1);
        }

        return $matches->map(fn (Project $project) => [
            'type' => 'Project',
            'title' => $project->title,
            'excerpt' => Str::limit(strip_tags($project->description), 160),
            'url' => route('projects.show', $project->slug),
            'relevance' => (float) $project->relevance,
        ]);
    }

    private function searchPosts(string $query, int $limit): Collection
    {
        $matches = Post::where('status', 'published')
            ->selectRaw('*, MATCH(title, excerpt, body, ai_summary) AGAINST (? IN NATURAL LANGUAGE MODE) as relevance', [$query])
            ->whereFullText(['title', 'excerpt', 'body', 'ai_summary'], $query)
            ->orderByDesc('relevance')
            ->limit($limit)
            ->get();

        if ($matches->isEmpty()) {
            $matches = Post::where('status', 'published')
                ->where('title', 'like', "%{$query}%")
                ->limit($limit)
                ->get()
                ->each(fn ($p) => $p->relevance = 0.1);
        }

        return $matches->map(fn (Post $post) => [
            'type' => 'Blog Post',
            'title' => $post->title,
            'excerpt' => Str::limit(strip_tags($post->excerpt ?: $post->ai_summary ?: $post->body), 160),
            'url' => route('blog.show', $post->slug),
            'relevance' => (float) $post->relevance,
        ]);
    }
}
