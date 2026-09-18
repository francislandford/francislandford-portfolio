<?php

namespace App\Services\Ai;

use App\Models\Project;
use App\Models\Service;
use App\Models\Setting;
use App\Models\Skill;
use App\Models\Post;

/**
 * Simple keyword-based retrieval over the site's own content, used to ground
 * the "Ask my portfolio" chatbot. Deliberately not embeddings-based: the
 * corpus (a handful of services/projects/posts) is small enough that MySQL
 * full-text search gives good-enough relevance without needing a second paid
 * API just for embeddings. Falls back to "most recent" when a query matches
 * nothing, so the model is never left with empty context.
 */
class PortfolioRetriever
{
    /**
     * @return array<int, array{type: string, title: string, content: string, url: string}>
     */
    public function retrieve(string $question, int $perType = 4): array
    {
        $chunks = [];

        foreach ($this->services($question, $perType) as $service) {
            $chunks[] = [
                'type' => 'Service',
                'title' => $service->title,
                'content' => trim($service->summary.' '.strip_tags((string) $service->body)),
                'url' => route('services.show', $service->slug),
            ];
        }

        foreach ($this->projects($question, $perType) as $project) {
            $chunks[] = [
                'type' => 'Project',
                'title' => $project->title.($project->client ? " (client: {$project->client})" : ''),
                'content' => trim($project->description.' '.strip_tags((string) $project->body)),
                'url' => route('projects.show', $project->slug),
            ];
        }

        foreach ($this->posts($question, $perType) as $post) {
            $chunks[] = [
                'type' => 'Blog Post',
                'title' => $post->title,
                'content' => trim(($post->excerpt ?: $post->ai_summary).' '.strip_tags((string) $post->body)),
                'url' => route('blog.show', $post->slug),
            ];
        }

        return $chunks;
    }

    /**
     * A short profile block that's always included, since "who is this" /
     * "what do you do" / "how do I reach you" questions are extremely common
     * and this is small enough to always afford.
     */
    public function siteProfile(): string
    {
        $skills = Skill::where('is_active', true)->orderBy('order')->pluck('name')->implode(', ');

        return implode("\n", array_filter([
            'Name: '.Setting::get('name'),
            'Tagline: '.Setting::get('tagline'),
            'About: '.Setting::get('hero_subheadline'),
            'Skills/tech stack: '.($skills ?: 'not listed'),
            'Contact email: '.Setting::get('email'),
            'Contact page: '.route('contact'),
        ]));
    }

    private function services(string $question, int $limit)
    {
        $matches = Service::where('is_active', true)
            ->whereFullText(['title', 'summary', 'body'], $question)
            ->limit($limit)
            ->get();

        return $matches->isNotEmpty()
            ? $matches
            : Service::where('is_active', true)->orderBy('order')->limit($limit)->get();
    }

    private function projects(string $question, int $limit)
    {
        $matches = Project::where('status', 'published')
            ->whereFullText(['title', 'description', 'body'], $question)
            ->limit($limit)
            ->get();

        return $matches->isNotEmpty()
            ? $matches
            : Project::where('status', 'published')->orderByDesc('is_featured')->orderBy('order')->limit($limit)->get();
    }

    private function posts(string $question, int $limit)
    {
        $matches = Post::where('status', 'published')
            ->whereFullText(['title', 'excerpt', 'body', 'ai_summary'], $question)
            ->limit($limit)
            ->get();

        return $matches->isNotEmpty()
            ? $matches
            : Post::where('status', 'published')->latest('published_at')->limit($limit)->get();
    }
}
