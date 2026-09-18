<?php

namespace App\Contracts;

use App\Models\UsageLog;

interface BillableService
{
    /**
     * The identifier used to group usage/cost for this service (e.g. "openai_chat", "sms", "storage").
     */
    public function billableServiceName(): string;

    /**
     * Record a usage/cost entry for a call made by this service.
     */
    public function logUsage(string $action, float $cost, ?int $tokensUsed = null, array $meta = [], $subject = null): UsageLog;
}
