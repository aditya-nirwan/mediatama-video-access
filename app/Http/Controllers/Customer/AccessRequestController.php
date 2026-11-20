<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Http\Requests\Customer\StoreAccessRequestRequest;
use App\Models\Video;
use App\Services\VideoAccessService;
use DomainException;
use Illuminate\Http\RedirectResponse;

class AccessRequestController extends Controller
{
    public function __construct(private readonly VideoAccessService $service)
    {
    }

    public function __invoke(StoreAccessRequestRequest $request, Video $video): RedirectResponse
    {
        try {
            $this->service->requestAccess(
                $request->user(),
                $video,
                $request->input('reason')
            );
        } catch (DomainException $exception) {
            return back()->withErrors(['reason' => $exception->getMessage()]);
        }

        return back()->with('status', "Permintaan akses untuk {$video->title} sudah dikirim ke admin.");
    }
}