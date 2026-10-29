<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'slug',
        'name',
        'icon',
        'description',
        'seo_title',
        'seo_description',
        'is_active',
        'sort_order',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'sort_order' => 'integer',
        ];
    }

    public function posts(): BelongsToMany
    {
        return $this->belongsToMany(Post::class)->withTimestamps();
    }

    public function localizations(): HasMany
    {
        return $this->hasMany(CategoryLocalization::class);
    }

    public function localization(string $locale): ?CategoryLocalization
    {
        return $this->localizations
            ->first(fn (CategoryLocalization $localization) => $localization->locale === $locale && $localization->is_active);
    }
}
