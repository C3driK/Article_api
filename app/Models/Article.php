<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Article extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'published_at' => 'datetime',
        'expired_at' => 'datetime',
    ];

    protected $hidden = [
        'id',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function (self $model) {
            $model->uuid = $model->generateUuid();
        });
    }

    /**
     * Generate a unique UUID string.
     */
    public function generateUuid(): string
    {
        $string = (string) Str::uuid();

        if (static::query()->where('uuid', $string)->exists()) {
            return $this->generateUuid();
        }

        return $string;
    }

    /**
     * Scope query to only include unexpired articles.
     */
    public function scopeActive(Builder $query): Builder
    {
        return $query->whereNull('expired_at')->OrWhere('expired_at', '>', now());
    }

    /**
     * Scope query to only select needed fields when getting all articles.
     */
    public static function scopeWithListFields(Builder $query): Builder
    {
        $fields = ['uuid', 'name', 'author_name', 'sentiment', 'published_at', 'created_at', 'updated_at'];

        return $query->select($fields);
    }
}
