<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class VideoAccess extends Model
{
    use HasFactory;

    protected $table = 'video_accesses';

    protected $fillable = [
        'user_id',
        'video_id',
        'access_request_id',
        'granted_by',
        'starts_at',
        'expires_at',
        'revoked_at',
        'revoked_by',
    ];

    protected function casts(): array
    {
        return [
            'starts_at' => 'datetime',
            'expires_at' => 'datetime',
            'revoked_at' => 'datetime',
        ];
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->whereNull('revoked_at')
            ->where('starts_at', '<=', now())
            ->where('expires_at', '>', now());
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function video(): BelongsTo
    {
        return $this->belongsTo(Video::class);
    }

    public function granter(): BelongsTo
    {
        return $this->belongsTo(User::class, 'granted_by');
    }

    public function request(): BelongsTo
    {
        return $this->belongsTo(AccessRequest::class, 'access_request_id');
    }

    public function watchLog(): HasOne
    {
        return $this->hasOne(WatchLog::class);
    }

    public function isRevoked(): bool
    {
        return $this->revoked_at !== null;
    }

    public function isExpired(): bool
    {
        return ! $this->isRevoked() && $this->expires_at->isPast();
    }

    public function isActive(): bool
    {
        return ! $this->isRevoked()
            && $this->starts_at->lessThanOrEqualTo(now())
            && $this->expires_at->isFuture();
    }

    public function remainingSeconds(): int
    {
        return $this->isActive()
            ? (int) now()->diffInSeconds($this->expires_at, false)
            : 0;
    }
}
