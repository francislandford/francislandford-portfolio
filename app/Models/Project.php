<?php

namespace App\Models;

use App\Support\HasSlug;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Project extends Model
{
    use HasSlug;

    protected $fillable = [
        'title',
        'slug',
        'client',
        'start_date',
        'live_url',
        'description',
        'body',
        'cover_image',
        'status',
        'is_featured',
        'order',
        'meta_title',
        'meta_description',
        'og_image',
    ];

    protected $casts = [
        'start_date' => 'date',
        'is_featured' => 'boolean',
    ];

    protected function slugSourceField(): string
    {
        return 'title';
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(Category::class);
    }

    public function skills(): BelongsToMany
    {
        return $this->belongsToMany(Skill::class);
    }

    public function testimonials(): HasMany
    {
        return $this->hasMany(Testimonial::class);
    }
}
