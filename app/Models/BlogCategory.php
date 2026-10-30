<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class BlogCategory extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'is_active', 'sort_order'];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'sort_order' => 'integer',
        ];
    }

    public function localizations(): HasMany
    {
        return $this->hasMany(BlogCategoryLocalization::class);
    }

    public function genres(): BelongsToMany
    {
        return $this->belongsToMany(BlogGenre::class)->withTimestamps();
    }

    public function posts(): HasMany
    {
        return $this->hasMany(Post::class);
    }

    public function localization(string $locale): ?BlogCategoryLocalization
    {
        return $this->localizations
            ->first(fn (BlogCategoryLocalization $localization) => $localization->locale === $locale && $localization->is_active);
    }
}
