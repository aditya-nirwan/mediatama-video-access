<?php

namespace App\Services;

use App\Enums\RequestStatus;
use App\Models\AccessRequest;
use App\Models\User;
use App\Models\Video;
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
}