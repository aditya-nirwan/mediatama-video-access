<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ApproveAccessRequestRequest;
use App\Models\AccessRequest;
use App\Services\VideoAccessService;
use App\Support\AccessDuration;
use DomainException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AccessRequestController extends Controller
{
    public function __construct(private readonly VideoAccessService $service)
    {
    }

    public function index(): View
    {
        return view('admin.requests.index', [
            'pending' => AccessRequest::pending()
                ->with(['user', 'video'])
                ->oldest()
                ->get(),
            'processed' => AccessRequest::whereNot('status', \App\Enums\RequestStatus::Pending)
                ->with(['user', 'video', 'responder'])
                ->latest('responded_at')
                ->paginate(10),
        ]);
    }

    public function approve(ApproveAccessRequestRequest $request, AccessRequest $accessRequest): RedirectResponse
    {
        try {
            $this->service->approve(
                $accessRequest,
                $request->user(),
                AccessDuration::resolve($request->input('duration'), $request->input('expires_at')),
                $request->input('admin_note')
            );
        } catch (DomainException $exception) {
            return back()->withErrors(['duration' => $exception->getMessage()]);
        }

        return back()->with('status', "Akses untuk {$accessRequest->user->name} sudah diberikan.");
    }

    public function reject(Request $request, AccessRequest $accessRequest): RedirectResponse
    {
        $validated = $request->validate([
            'admin_note' => ['nullable', 'string', 'max:500'],
        ]);

        try {
            $this->service->reject($accessRequest, $request->user(), $validated['admin_note'] ?? null);
        } catch (DomainException $exception) {
            return back()->withErrors(['admin_note' => $exception->getMessage()]);
        }

        return back()->with('status', "Permintaan {$accessRequest->user->name} sudah ditolak.");
    }
}