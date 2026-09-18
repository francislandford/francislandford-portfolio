<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Achievement extends Model
{
    protected $fillable = [
        'title',
        'description',
        'category',
        'organization',
        'date',
        'url',
        'order',
    ];

    protected $casts = [
        'date' => 'date',
    ];
}
