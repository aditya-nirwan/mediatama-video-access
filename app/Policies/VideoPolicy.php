<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Video;

class VideoPolicy
{
    public function stream(User $user, Video $video): bool
    {
        if ($user->isAdmin()) {
            return true;
        }

        if (! $video->is_published) {
            return false;
        }

        return $user->activeAccessFor($video) !== null;
    }
}
