<?php

namespace App\Services\Ai;

use Illuminate\Support\Facades\Http;
use RuntimeException;

/**
 * Fetches a public repository's README as plain text via the unauthenticated
 * GitHub API (the `application/vnd.github.raw` Accept header returns raw
 * markdown directly, no base64 decoding needed).
 */
class GitHubReadmeService
{
    public function fetchReadme(string $repoUrlOrSlug): string
    {
        $slug = $this->parseRepoSlug($repoUrlOrSlug);

        $response = Http::withHeaders([
            'Accept' => 'application/vnd.github.raw',
            'User-Agent' => 'francislandford-portfolio-app',
        ])->get("https://api.github.com/repos/{$slug}/readme");

        if ($response->status() === 404) {
            throw new RuntimeException("No README found for {$slug} (is the repository public?).");
        }

        if (! $response->successful()) {
            throw new RuntimeException("Could not fetch the README for {$slug}: ".$response->body());
        }

        return $response->body();
    }

    private function parseRepoSlug(string $input): string
    {
        $input = trim($input);

        if (preg_match('~github\.com/([\w.-]+)/([\w.-]+?)(?:\.git)?/?(?:$|[?#])~i', $input, $matches)) {
            return "{$matches[1]}/{$matches[2]}";
        }

        if (preg_match('~^[\w.-]+/[\w.-]+$~', $input)) {
            return $input;
        }

        throw new RuntimeException("Could not parse a GitHub repository from \"{$input}\". Use a URL like https://github.com/owner/repo, or just \"owner/repo\".");
    }
}
