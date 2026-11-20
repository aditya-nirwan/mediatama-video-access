<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Video;
use App\Services\VideoAccessService;
use DomainException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class AccessRequestController extends Controller
{
    public function __construct(private readonly VideoAccessService $service)
    {
    }

    public function __invoke(Request $request, Video $video): RedirectResponse
    {
        $validated = $request->validate([
            'reason' => ['nullable', 'string', 'max:500'],
        ]);

        try {
            $this->service->requestAccess(
                $request->user(),
                $video,
                $validated['reason'] ?? null
            );
        } catch (DomainException $exception) {
            return back()->withErrors(['reason' => $exception->getMessage()]);
        }

        return back()->with('status', "Permintaan akses untuk {$video->title} sudah dikirim ke admin.");
    }
}