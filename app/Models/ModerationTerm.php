<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ModerationTerm extends Model
{
    protected $fillable = ['phrase', 'locale', 'classification', 'is_active'];
    protected function casts(): array { return ['is_active' => 'boolean']; }
}
