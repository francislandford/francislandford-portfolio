<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Publication extends Model
{
    protected $fillable = [
        'title',
        'publisher',
        'excerpt',
        'url',
        'published_at',
        'type',
        'order',
    ];

    protected $casts = [
        'published_at' => 'date',
    ];
}
