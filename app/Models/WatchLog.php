<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WatchLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'video_access_id',
        'user_id',
        'video_id',
        'last_position_seconds',
        'watch_count',
        'ip_address',
        'user_agent',
        'first_watched_at',
        'last_watched_at',
    ];

    protected function casts(): array
    {
        return [
            'last_position_seconds' => 'integer',
            'watch_count' => 'integer',
            'first_watched_at' => 'datetime',
            'last_watched_at' => 'datetime',
        ];
    }

    public function access(): BelongsTo
    {
        return $this->belongsTo(VideoAccess::class, 'video_access_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function video(): BelongsTo
    {
        return $this->belongsTo(Video::class);
    }
}
