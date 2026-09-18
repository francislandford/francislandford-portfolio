<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Certification extends Model
{
    protected $fillable = [
        'title',
        'issuer',
        'issued_at',
        'order',
    ];

    protected $casts = [
        'issued_at' => 'date',
    ];
}
