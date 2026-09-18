<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class UsageLog extends Model
{
    protected $fillable = [
        'service',
        'action',
        'tokens_used',
        'cost',
        'currency',
        'subject_type',
        'subject_id',
        'meta',
    ];

    protected $casts = [
        'cost' => 'decimal:4',
        'meta' => 'array',
    ];

    public function subject(): MorphTo
    {
        return $this->morphTo();
    }
}
