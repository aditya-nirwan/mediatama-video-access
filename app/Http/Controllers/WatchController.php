<?php

namespace App\Http\Controllers;

use App\Models\Video;
use App\Services\VideoAccessService;
use Illuminate\Http\Request;
use Illuminate\View\View;

class WatchController extends Controller
{
    public function __construct(private readonly VideoAccessService $service)
    {
    }

    public function __invoke(Request $request, Video $video): View
    {
        $this->authorize('stream', $video);

        $access = $request->user()->activeAccessFor($video);
        $log = null;

        if ($access !== null) {
            $log = $this->service->logWatch($access, $request->ip(), $request->userAgent());
        }

        return view('videos.watch', [
            'video' => $video,
            'access' => $access,
            'remainingSeconds' => $access?->remainingSeconds() ?? 0,
            'resumeAt' => $log?->last_position_seconds ?? 0,
        ]);
    }
}