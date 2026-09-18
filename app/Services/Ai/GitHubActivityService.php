<?php

namespace App\Services\Ai;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Http;
use RuntimeException;

/**
 * Pulls a GitHub user's recent PUBLIC activity via the unauthenticated
 * Events API. No token required: this is called at most a few times a day
 * (on-demand or via a scheduled digest job), well under the 60 req/hour
 * unauthenticated rate limit.
 *
 * Note: GitHub's public events payload for PushEvent no longer includes
 * commit messages (only ref/head/before SHAs), so pushes are summarized by
 * repo + frequency rather than by commit message content.
 */
class GitHubActivityService
{
    /**
     * @return string[] Short human-readable descriptions, most active first.
     */
    public function fetchRecentActivity(string $username): array
    {
        $response = Http::withHeaders([
            'Accept' => 'application/vnd.github+json',
            'User-Agent' => 'francislandford-portfolio-app',
        ])->get("https://api.github.com/users/{$username}/events/public");

        if ($response->status() === 404) {
            throw new RuntimeException("GitHub user \"{$username}\" was not found.");
        }

        if (! $response->successful()) {
            throw new RuntimeException("Could not fetch GitHub activity for {$username}: ".$response->body());
        }

        $events = collect($response->json());

        return $events
            ->groupBy(fn (array $event) => ($event['type'] ?? 'Unknown').'|'.($event['repo']['name'] ?? 'unknown'))
            ->map(fn (Collection $group, string $key) => $this->describeGroup($key, $group))
            ->filter()
            ->values()
            ->all();
    }

    private function describeGroup(string $key, Collection $events): ?string
    {
        [$type, $repo] = explode('|', $key, 2);
        $count = $events->count();
        $payload = $events->first()['payload'] ?? [];

        return match ($type) {
            'PushEvent' => $count > 1
                ? "Pushed to {$repo} ({$count} pushes)"
                : "Pushed to {$repo}",
            'PullRequestEvent' => sprintf(
                '%s pull request in %s: "%s"',
                ucfirst($payload['action'] ?? 'updated'),
                $repo,
                $payload['pull_request']['title'] ?? ''
            ),
            'IssuesEvent' => sprintf(
                '%s issue in %s: "%s"',
                ucfirst($payload['action'] ?? 'updated'),
                $repo,
                $payload['issue']['title'] ?? ''
            ),
            'CreateEvent' => ($payload['ref_type'] ?? null) === 'repository'
                ? "Created new repository {$repo}"
                : null,
            'ReleaseEvent' => sprintf('Published release %s in %s', $payload['release']['tag_name'] ?? '', $repo),
            default => null,
        };
    }
}
