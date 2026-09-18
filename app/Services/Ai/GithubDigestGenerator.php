<?php

namespace App\Services\Ai;

use App\Models\GithubDigest;
use App\Models\Setting;
use RuntimeException;

class GithubDigestGenerator
{
    public function __construct(
        private readonly GitHubActivityService $activity,
        private readonly AnthropicDigestService $digestService,
    ) {}

    public function run(): GithubDigest
    {
        $username = Setting::get('github_username');

        if (empty($username)) {
            throw new RuntimeException('No GitHub username is configured. Add a "github_username" setting first.');
        }

        $activityLines = $this->activity->fetchRecentActivity($username);
        $content = $this->digestService->generate($activityLines);

        return GithubDigest::create([
            'content' => $content,
            'events_count' => count($activityLines),
            'period_start' => now()->subDays(90),
            'period_end' => now(),
            'generated_at' => now(),
        ]);
    }
}
