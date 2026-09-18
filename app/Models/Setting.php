<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Setting extends Model
{
    protected $fillable = [
        'group',
        'key',
        'value',
        'type',
    ];

    public static function get(string $key, mixed $default = null): mixed
    {
        // Cached as a plain array, not a Collection: this PHP build has a
        // class-autoload bug when unserializing objects out of the database
        // cache store, so keep cached cache payloads to plain scalars/arrays.
        $values = Cache::rememberForever('settings', fn () => static::all()->pluck('value', 'key')->all());

        return $values[$key] ?? $default;
    }

    public static function set(string $key, mixed $value, string $group = 'general', string $type = 'string'): self
    {
        $setting = static::updateOrCreate(
            ['key' => $key],
            ['value' => $value, 'group' => $group, 'type' => $type]
        );

        Cache::forget('settings');

        return $setting;
    }

    protected static function booted(): void
    {
        static::saved(fn () => Cache::forget('settings'));
        static::deleted(fn () => Cache::forget('settings'));
    }
}
