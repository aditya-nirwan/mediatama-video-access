<?php

namespace App\Support;

use App\Enums\RequestStatus;
use App\Models\AccessRequest;
use App\Models\VideoAccess;

final class VideoAccessState
{
    private function __construct(
        public readonly ?VideoAccess $access,
        public readonly ?AccessRequest $request,
    ) {
    }

    public static function make(?VideoAccess $access, ?AccessRequest $request): self
    {
        return new self($access, $request);
    }

    public function isWatchable(): bool
    {
        return $this->access !== null && $this->access->isActive();
    }

    public function isPending(): bool
    {
        return $this->request !== null && $this->request->status === RequestStatus::Pending;
    }

    public function isExpired(): bool
    {
        return ! $this->isWatchable()
            && $this->access !== null
            && $this->access->isExpired();
    }

    public function wasRevoked(): bool
    {
        return ! $this->isWatchable()
            && $this->access !== null
            && $this->access->isRevoked();
    }

    public function isRejected(): bool
    {
        return ! $this->isWatchable()
            && ! $this->isPending()
            && $this->access === null
            && $this->request !== null
            && $this->request->status === RequestStatus::Rejected;
    }

    public function canRequest(): bool
    {
        return ! $this->isWatchable() && ! $this->isPending();
    }

    public function label(): string
    {
        return match (true) {
            $this->isWatchable() => 'Akses aktif',
            $this->isPending() => 'Menunggu persetujuan',
            $this->wasRevoked() => 'Akses dicabut',
            $this->isExpired() => 'Masa akses habis',
            $this->isRejected() => 'Permintaan ditolak',
            default => 'Belum punya akses',
        };
    }

    public function badgeColor(): string
    {
        return match (true) {
            $this->isWatchable() => 'green',
            $this->isPending() => 'amber',
            $this->wasRevoked(), $this->isExpired(), $this->isRejected() => 'red',
            default => 'gray',
        };
    }
}