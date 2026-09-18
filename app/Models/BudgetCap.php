<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BudgetCap extends Model
{
    protected $fillable = [
        'service',
        'monthly_limit',
        'notify_at_percent',
        'is_enabled',
    ];

    protected $casts = [
        'monthly_limit' => 'decimal:2',
        'is_enabled' => 'boolean',
    ];

    public function currentMonthSpend(): float
    {
        return (float) UsageLog::where('service', $this->service)
            ->whereBetween('created_at', [now()->startOfMonth(), now()->endOfMonth()])
            ->sum('cost');
    }

    public function isExceeded(): bool
    {
        if (! $this->is_enabled || $this->monthly_limit === null) {
            return false;
        }

        return $this->currentMonthSpend() >= (float) $this->monthly_limit;
    }
}
