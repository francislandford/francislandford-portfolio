<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GithubDigest extends Model
{
    protected $fillable = [
        'content',
        'events_count',
        'period_start',
        'period_end',
        'generated_at',
    ];

    protected $casts = [
        'period_start' => 'datetime',
        'period_end' => 'datetime',
        'generated_at' => 'datetime',
    ];
}
