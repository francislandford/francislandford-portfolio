<?php

namespace App\Models;

use App\Support\HasSlug;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Lesson extends Model
{
    use HasSlug;

    protected $fillable = [
        'course_id',
        'type',
        'title',
        'slug',
        'body',
        'video_url',
        'order',
        'is_free_preview',
    ];

    protected $casts = [
        'is_free_preview' => 'boolean',
    ];

    protected function slugSourceField(): string
    {
        return 'title';
    }

    protected function generateUniqueSlug(): string
    {
        $base = Str::slug($this->title);
        $slug = $base;
        $i = 1;

        while (
            static::where('course_id', $this->course_id)
                ->where('slug', $slug)
                ->when($this->exists, fn ($q) => $q->where('id', '!=', $this->id))
                ->exists()
        ) {
            $slug = "{$base}-{$i}";
            $i++;
        }

        return $slug;
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }

    public function attachments(): HasMany
    {
        return $this->hasMany(LessonAttachment::class)->orderBy('order');
    }

    public function bodyHtml(): string
    {
        return Str::markdown($this->body ?? '', [
            'html_input' => 'escape',
        ]);
    }
}
