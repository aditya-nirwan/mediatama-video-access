<?php

namespace App\Http\Controllers\Admin;

use App\Enums\UserRole;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreVideoAccessRequest;
use App\Models\User;
use App\Models\Video;
use App\Models\VideoAccess;
use App\Services\VideoAccessService;
use App\Support\AccessDuration;
use DomainException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class VideoAccessController extends Controller
{
    public function __construct(private readonly VideoAccessService $service)
    {
    }

    public function index(Request $request): View
    {
        $filter = $request->query('filter', 'active');

        $accesses = VideoAccess::query()
            ->with(['user', 'video', 'granter'])
            ->when($filter === 'active', fn ($query) => $query->active())
            ->when($filter === 'expired', fn ($query) => $query
                ->whereNull('revoked_at')
                ->where('expires_at', '<=', now()))
            ->when($filter === 'revoked', fn ($query) => $query->whereNotNull('revoked_at'))
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('admin.accesses.index', [
            'accesses' => $accesses,
            'filter' => $filter,
            'customers' => User::customers()->where('is_active', true)->orderBy('name')->get(),
            'videos' => Video::orderBy('title')->get(),
        ]);
    }

    public function store(StoreVideoAccessRequest $request): RedirectResponse
    {
        $customer = User::findOrFail($request->input('user_id'));
        $video = Video::findOrFail($request->input('video_id'));

        try {
            $this->service->grantDirect(
                $customer,
                $video,
                $request->user(),
                AccessDuration::resolve($request->input('duration'), $request->input('expires_at'))
            );
        } catch (DomainException $exception) {
            return back()->withErrors(['user_id' => $exception->getMessage()]);
        }

        return back()->with('status', "Akses {$video->title} diberikan ke {$customer->name}.");
    }

    public function extend(Request $request, VideoAccess $access): RedirectResponse
    {
        $validated = $request->validate([
            'duration' => ['required', \Illuminate\Validation\Rule::in(AccessDuration::keys())],
        ]);

        try {
            $this->service->extend($access, AccessDuration::minutes($validated['duration']));
        } catch (DomainException $exception) {
            return back()->withErrors(['duration' => $exception->getMessage()]);
        }

        return back()->with('status', 'Masa akses berhasil diperpanjang.');
    }

    public function destroy(Request $request, VideoAccess $access): RedirectResponse
    {
        try {
            $this->service->revoke($access, $request->user());
        } catch (DomainException $exception) {
            return back()->withErrors(['access' => $exception->getMessage()]);
        }

        return back()->with('status', 'Akses berhasil dicabut.');
    }
}