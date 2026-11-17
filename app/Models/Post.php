<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use App\Support\EnglishGroup;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Post extends Model
{
    use HasFactory;

    protected $fillable = [
        'author_id',
        'status',
        'publication_type',
        'blog_category_id',
        'is_featured',
        'published_at',
    ];

    protected function casts(): array
    {
        return [
            'is_featured' => 'boolean',
            'published_at' => 'datetime',
        ];
    }

    public function author(): BelongsTo { return $this->belongsTo(User::class, 'author_id'); }
    public function blogCategory(): BelongsTo { return $this->belongsTo(BlogCategory::class); }
    public function localizations(): HasMany { return $this->hasMany(PostLocalization::class); }
    public function media(): HasMany { return $this->hasMany(Media::class)->orderBy('sort_order')->orderBy('id'); }
    public function categories(): BelongsToMany { return $this->belongsToMany(Category::class)->withTimestamps(); }
    public function genres(): BelongsToMany { return $this->belongsToMany(Genre::class)->withTimestamps(); }
    public function blogGenres(): BelongsToMany { return $this->belongsToMany(BlogGenre::class)->withTimestamps(); }
    public function comments(): HasMany { return $this->hasMany(Comment::class); }
    public function reactions(): HasMany { return $this->hasMany(Reaction::class); }
    public function favoritedBy(): BelongsToMany { return $this->belongsToMany(User::class, 'user_favorites')->withTimestamps(); }
    public function views(): HasMany { return $this->hasMany(PostView::class); }

    public function localization(?string $locale = null): ?PostLocalization
    {
        $locale ??= config('localization.default');

        return $this->localizations
            ->first(fn (PostLocalization $localization) => $localization->locale === $locale && $localization->is_active);
    }

    public function scopePublished(Builder $query): Builder
    {
        return $query->where('status', 'published')->whereNotNull('published_at')->where('published_at', '<=', now());
    }

    public function scopeTrending(Builder $query, string $locale, int $days = 2): Builder
    {
        $since = now()->subDays($days);
        // Exact timing remains available for likes/comments. Aggregated views
        // are counted by calendar day, so their window starts at midnight.
        $viewSince = $since->copy()->startOfDay();

        return $query
            ->withCount([
                'reactions as recent_likes_count' => fn (Builder $q) => $q->whereIn('locale', EnglishGroup::history($locale))->where('type', 'like')->where('created_at', '>=', $since),
                'comments as recent_comments_count' => fn (Builder $q) => $q->whereIn('locale', EnglishGroup::history($locale))->where('status', 'approved')->where('created_at', '>=', $since),
            ])
            ->withSum(['views as recent_views_count' => fn (Builder $q) => $q->whereIn('locale', EnglishGroup::history($locale))->where('viewed_at', '>=', $viewSince)], 'view_count')
            ->orderByRaw('(COALESCE(recent_views_count, 0) + (recent_likes_count * 3) + (recent_comments_count * 5)) DESC');
    }
}
