<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class BlogGenre extends Model
{
    use HasFactory;

    protected $fillable = ['is_active', 'sort_order'];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'sort_order' => 'integer',
        ];
    }

    public function localizations(): HasMany
    {
        return $this->hasMany(BlogGenreLocalization::class);
    }

    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(BlogCategory::class)->withTimestamps();
    }

    public function posts(): BelongsToMany
    {
        return $this->belongsToMany(Post::class)->withTimestamps();
    }

    public function localization(string $locale): ?BlogGenreLocalization
    {
        return $this->localizations
            ->first(fn (BlogGenreLocalization $localization) => $localization->locale === $locale && $localization->is_active);
    }
}
