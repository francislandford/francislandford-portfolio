<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TrustedCompany extends Model
{
    protected $fillable = [
        'name',
        'logo',
        'url',
        'order',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];
}
