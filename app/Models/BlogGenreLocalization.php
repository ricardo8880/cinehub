<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BlogGenreLocalization extends Model
{
    use HasFactory;

    protected $fillable = [
        'locale',
        'name',
        'slug',
        'is_active',
    ];

    protected function casts(): array
    {
        return ['is_active' => 'boolean'];
    }

    public function genre(): BelongsTo
    {
        return $this->belongsTo(BlogGenre::class, 'blog_genre_id');
    }
}
