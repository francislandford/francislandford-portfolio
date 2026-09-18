<?php

namespace App\Console\Commands;

use App\Services\Ai\GithubDigestGenerator;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;
use Illuminate\Support\Str;

#[Signature('app:generate-github-digest')]
#[Description("Generate a fresh \"what I've been working on\" digest from recent public GitHub activity.")]
class GenerateGithubDigest extends Command
{
    public function handle(GithubDigestGenerator $generator): int
    {
        try {
            $digest = $generator->run();

            $this->info('Digest generated: '.Str::limit($digest->content, 80));

            return self::SUCCESS;
        } catch (\Throwable $e) {
            $this->error($e->getMessage());

            return self::FAILURE;
        }
    }
}
