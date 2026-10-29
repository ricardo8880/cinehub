<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AdminActivityLog extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'method', 'route_name', 'path', 'ip_address', 'user_agent', 'status_code'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
