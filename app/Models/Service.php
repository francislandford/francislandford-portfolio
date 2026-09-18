<?php

namespace App\Models;

use App\Support\HasSlug;
use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    use HasSlug;

    protected $fillable = [
        'title',
        'slug',
        'icon',
        'summary',
        'body',
        'order',
        'is_active',
        'meta_title',
        'meta_description',
        'og_image',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    protected function slugSourceField(): string
    {
        return 'title';
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }
}
