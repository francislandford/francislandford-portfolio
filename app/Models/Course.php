<?php

namespace App\Models;

use App\Support\HasSlug;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Course extends Model
{
    use HasSlug;

    protected $fillable = [
        'title',
        'slug',
        'excerpt',
        'description',
        'body',
        'cover_image',
        'price',
        'currency',
        'level',
        'status',
        'order',
        'meta_title',
        'meta_description',
        'og_image',
    ];

    protected $casts = [
        'price' => 'decimal:2',
    ];

    protected function slugSourceField(): string
    {
        return 'title';
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function isFree(): bool
    {
        return is_null($this->price) || (float) $this->price <= 0;
    }

    public function lessons(): HasMany
    {
        return $this->hasMany(Lesson::class)->orderBy('order');
    }

    public function quiz(): HasOne
    {
        return $this->hasOne(Quiz::class);
    }

    public function enrollments(): HasMany
    {
        return $this->hasMany(Enrollment::class);
    }

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }
}
