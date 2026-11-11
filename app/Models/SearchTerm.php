<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SearchTerm extends Model
{
    protected $fillable = [
        'locale',
        'term',
        'normalized_term',
        'search_count',
        'last_result_count',
        'first_searched_at',
        'last_searched_at',
    ];

    protected function casts(): array
    {
        return [
            'search_count' => 'integer',
            'last_result_count' => 'integer',
            'first_searched_at' => 'datetime',
            'last_searched_at' => 'datetime',
        ];
    }
}
