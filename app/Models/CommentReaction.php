<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CommentReaction extends Model
{
    public $timestamps = true;
    protected $fillable = ['user_id'];

    public function comment(): BelongsTo { return $this->belongsTo(Comment::class); }
    public function user(): BelongsTo { return $this->belongsTo(User::class); }
}
