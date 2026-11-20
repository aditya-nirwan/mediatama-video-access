<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Video;
use App\Models\VideoAccess;
use App\Support\VideoAccessState;
use Illuminate\Http\Request;
use Illuminate\View\View;

class VideoController extends Controller
{
    public function __invoke(Request $request): View
    {
        $userId = $request->user()->id;

        $videos = Video::query()
            ->where('is_published', true)
            ->with([
                'accesses' => fn ($query) => $query
                    ->where('user_id', $userId)
                    ->orderByDesc('expires_at'),
                'accessRequests' => fn ($query) => $query
                    ->where('user_id', $userId)
                    ->latest(),
            ])
            ->orderBy('title')
            ->paginate(9);

        $states = $videos->mapWithKeys(function (Video $video) {
            $access = $video->accesses->first(
                fn (VideoAccess $candidate) => $candidate->isActive()
            ) ?? $video->accesses->first();

            return [
                $video->id => VideoAccessState::make($access, $video->accessRequests->first()),
            ];
        });

        return view('customer.videos.index', compact('videos', 'states'));
    }
}