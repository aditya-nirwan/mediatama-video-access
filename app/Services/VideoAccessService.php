<?php

namespace App\Services;

use App\Enums\RequestStatus;
use App\Models\AccessRequest;
use App\Models\User;
use App\Models\Video;
use App\Enums\UserRole;
use App\Models\VideoAccess;
use Carbon\CarbonInterface;
use Illuminate\Support\Facades\DB;
use DomainException;

class VideoAccessService
{
    public function requestAccess(User $user, Video $video, ?string $reason = null): AccessRequest
    {
        if (! $video->is_published) {
            throw new DomainException('Video ini sedang tidak tersedia.');
        }

        if ($user->activeAccessFor($video) !== null) {
            throw new DomainException('Anda sudah memiliki akses aktif untuk video ini.');
        }

        $hasPending = $user->accessRequests()
            ->pending()
            ->where('video_id', $video->id)
            ->exists();

        if ($hasPending) {
            throw new DomainException('Permintaan Anda untuk video ini masih menunggu persetujuan admin.');
        }

        return AccessRequest::create([
            'user_id' => $user->id,
            'video_id' => $video->id,
            'reason' => $reason,
            'status' => RequestStatus::Pending,
        ]);
    }

    public function approve(
        AccessRequest $accessRequest,
        User $admin,
        CarbonInterface $expiresAt,
        ?string $note = null
    ): VideoAccess {
        $this->assertPending($accessRequest);

        if ($expiresAt->isPast()) {
            throw new DomainException('Waktu berakhir harus berada di masa depan.');
        }

        return DB::transaction(function () use ($accessRequest, $admin, $expiresAt, $note) {
            $accessRequest->update([
                'status' => RequestStatus::Approved,
                'responded_by' => $admin->id,
                'responded_at' => now(),
                'admin_note' => $note,
            ]);

            return VideoAccess::create([
                'user_id' => $accessRequest->user_id,
                'video_id' => $accessRequest->video_id,
                'access_request_id' => $accessRequest->id,
                'granted_by' => $admin->id,
                'starts_at' => now(),
                'expires_at' => $expiresAt,
            ]);
        });
    }

    public function reject(AccessRequest $accessRequest, User $admin, ?string $note = null): AccessRequest
    {
        $this->assertPending($accessRequest);

        $accessRequest->update([
            'status' => RequestStatus::Rejected,
            'responded_by' => $admin->id,
            'responded_at' => now(),
            'admin_note' => $note,
        ]);

        return $accessRequest;
    }

    public function grantDirect(
        User $customer,
        Video $video,
        User $admin,
        CarbonInterface $expiresAt
    ): VideoAccess {
        if ($customer->role !== UserRole::Customer) {
            throw new DomainException('Akses hanya dapat diberikan kepada customer.');
        }

        if ($customer->activeAccessFor($video) !== null) {
            throw new DomainException('Customer ini sudah memiliki akses aktif untuk video tersebut.');
        }

        if ($expiresAt->isPast()) {
            throw new DomainException('Waktu berakhir harus berada di masa depan.');
        }

        return VideoAccess::create([
            'user_id' => $customer->id,
            'video_id' => $video->id,
            'access_request_id' => null,
            'granted_by' => $admin->id,
            'starts_at' => now(),
            'expires_at' => $expiresAt,
        ]);
    }

    public function extend(VideoAccess $access, int $minutes): VideoAccess
    {
        if ($access->isRevoked()) {
            throw new DomainException('Akses yang sudah dicabut tidak dapat diperpanjang.');
        }

        $base = $access->expires_at->isPast() ? now() : $access->expires_at;

        $access->update(['expires_at' => $base->copy()->addMinutes($minutes)]);

        return $access;
    }

    public function revoke(VideoAccess $access, User $admin): VideoAccess
    {
        if ($access->isRevoked()) {
            throw new DomainException('Akses ini sudah dicabut sebelumnya.');
        }

        $access->update([
            'revoked_at' => now(),
            'revoked_by' => $admin->id,
        ]);

        return $access;
    }

    private function assertPending(AccessRequest $accessRequest): void
    {
        if ($accessRequest->status !== RequestStatus::Pending) {
            throw new DomainException('Permintaan ini sudah diproses sebelumnya.');
        }
    }
}