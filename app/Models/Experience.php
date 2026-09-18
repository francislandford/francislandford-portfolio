<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Experience extends Model
{
    protected $fillable = [
        'role_title',
        'company',
        'company_url',
        'location',
        'start_date',
        'end_date',
        'description',
        'order',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
    ];

    public function isCurrent(): bool
    {
        return $this->end_date === null;
    }
}
