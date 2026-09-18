<?php

namespace App\Services\Ai;

class AnthropicPricing
{
    /**
     * USD price per 1M tokens, by model id. Keep in step with the Anthropic pricing page.
     */
    private const PRICING = [
        'claude-opus-5' => ['input' => 5.00, 'output' => 25.00],
        'claude-sonnet-5' => ['input' => 2.00, 'output' => 10.00],
        'claude-haiku-4-5' => ['input' => 1.00, 'output' => 5.00],
    ];

    public static function cost(string $model, int $inputTokens, int $outputTokens): float
    {
        $pricing = self::PRICING[$model] ?? self::PRICING['claude-opus-5'];

        return ($inputTokens / 1_000_000 * $pricing['input'])
            + ($outputTokens / 1_000_000 * $pricing['output']);
    }
}
