<?php

namespace App\Models;

use App\Support\HasSlug;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Str;

class Category extends Model
{
    use HasSlug;

    protected $fillable = [
        'type',
        'name',
        'slug',
        'description',
    ];

    protected function slugSourceField(): string
    {
        return 'name';
    }

    protected function generateUniqueSlug(): string
    {
        $base = Str::slug($this->name);
        $slug = $base;
        $i = 1;

        while (
            static::where('type', $this->type)
                ->where('slug', $slug)
                ->when($this->exists, fn ($q) => $q->where('id', '!=', $this->id))
                ->exists()
        ) {
            $slug = "{$base}-{$i}";
            $i++;
        }

        return $slug;
    }

    public function posts(): BelongsToMany
    {
        return $this->belongsToMany(Post::class);
    }

    public function projects(): BelongsToMany
    {
        return $this->belongsToMany(Project::class);
    }
}
