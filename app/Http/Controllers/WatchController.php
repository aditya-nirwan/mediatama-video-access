<?php

namespace App\Http\Controllers;

use App\Models\Video;
use Illuminate\Http\Request;
use Illuminate\View\View;

class WatchController extends Controller
{
    public function __invoke(Request $request, Video $video): View
    {
        $this->authorize('stream', $video);

        return view('videos.watch', [
            'video' => $video,
            'access' => $request->user()->activeAccessFor($video),
        ]);
    }
}
