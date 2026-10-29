<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PostLocalization extends Model
{
    use HasFactory;

    protected $fillable = [
        'post_id',
        'locale',
        'is_active',
        'is_featured',
        'slug',
        'title',
        'excerpt',
        'synopsis',
        'review',
        'spoilers',
        'seo_title',
        'seo_description',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'is_featured' => 'boolean',
        ];
    }

    public function post(): BelongsTo
    {
        return $this->belongsTo(Post::class);
    }
}
