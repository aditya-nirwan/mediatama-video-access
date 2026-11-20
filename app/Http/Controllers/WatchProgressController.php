<?php

namespace App\Http\Controllers;

use App\Models\Video;
use App\Models\WatchLog;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class WatchProgressController extends Controller
{
    public function __invoke(Request $request, Video $video): JsonResponse
    {
        $this->authorize('stream', $video);

        $validated = $request->validate([
            'position' => ['required', 'integer', 'min:0', 'max:86400'],
        ]);

        $access = $request->user()->activeAccessFor($video);

        if ($access === null) {
            return response()->json(['saved' => false]);
        }

        WatchLog::where('video_access_id', $access->id)
            ->update(['last_position_seconds' => $validated['position']]);

        return response()->json(['saved' => true]);
    }
}