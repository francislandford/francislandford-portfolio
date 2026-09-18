<?php

namespace App\Support;

use App\Models\UsageLog;

/**
 * Implements the BillableService contract's logging behavior.
 * Classes using this trait must implement billableServiceName().
 */
trait LogsUsage
{
    abstract public function billableServiceName(): string;

    public function logUsage(string $action, float $cost, ?int $tokensUsed = null, array $meta = [], $subject = null): UsageLog
    {
        return UsageLog::create([
            'service' => $this->billableServiceName(),
            'action' => $action,
            'tokens_used' => $tokensUsed,
            'cost' => $cost,
            'subject_type' => $subject ? get_class($subject) : null,
            'subject_id' => $subject?->id,
            'meta' => $meta,
        ]);
    }
}
