<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreVideoRequest;
use App\Http\Requests\Admin\UpdateVideoRequest;
use App\Models\Video;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\View\View;

class VideoController extends Controller
{
    public function index(Request $request): View
    {
        $search = trim((string) $request->query('search', ''));

        $videos = Video::query()
            ->when($search !== '', fn ($query) => $query->where('title', 'like', "%{$search}%"))
            ->withCount([
                'accesses as active_access_count' => fn ($q) => $q->active(),
                'accessRequests as pending_request_count' => fn ($q) => $q->pending(),
            ])
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('admin.videos.index', compact('videos', 'search'));
    }

    public function create(): View
    {
        return view('admin.videos.create');
    }

    public function store(StoreVideoRequest $request): RedirectResponse
    {
        $video = new Video($request->safe()->only(['title', 'description', 'duration_seconds']));

        $video->slug = $this->uniqueSlug($request->input('title'));
        $video->uploaded_by = $request->user()->id;
        $video->is_published = $request->boolean('is_published');

        $this->attachVideoFile($video, $request->file('file'));

        if ($request->hasFile('thumbnail')) {
            $video->thumbnail_path = $request->file('thumbnail')->store('thumbnails', 'public');
        }

        $video->save();

        return redirect()
            ->route('admin.videos.index')
            ->with('status', "Video {$video->title} berhasil diunggah.");
    }

    public function edit(Video $video): View
    {
        return view('admin.videos.edit', compact('video'));
    }

    public function update(UpdateVideoRequest $request, Video $video): RedirectResponse
    {
        $video->fill($request->safe()->only(['title', 'description', 'duration_seconds']));

        if ($video->isDirty('title')) {
            $video->slug = $this->uniqueSlug($request->input('title'), $video->id);
        }

        $video->is_published = $request->boolean('is_published');

        if ($request->hasFile('file')) {
            Storage::disk('local')->delete($video->file_path);
            $this->attachVideoFile($video, $request->file('file'));
        }

        if ($request->hasFile('thumbnail')) {
            if ($video->thumbnail_path) {
                Storage::disk('public')->delete($video->thumbnail_path);
            }

            $video->thumbnail_path = $request->file('thumbnail')->store('thumbnails', 'public');
        }

        $video->save();

        return redirect()
            ->route('admin.videos.index')
            ->with('status', "Video {$video->title} berhasil diperbarui.");
    }

    public function destroy(Video $video): RedirectResponse
    {
        $title = $video->title;
        $video->delete();

        return redirect()
            ->route('admin.videos.index')
            ->with('status', "Video {$title} berhasil dihapus.");
    }

    private function attachVideoFile(Video $video, UploadedFile $file): void
    {
        $video->original_name = $file->getClientOriginalName();
        $video->mime_type = $file->getMimeType();
        $video->size_bytes = $file->getSize();
        $video->file_path = $file->store('videos', 'local');
    }

    private function uniqueSlug(string $title, ?int $ignoreId = null): string
    {
        $base = Str::slug($title);
        $slug = $base;
        $suffix = 2;

        while (
            Video::withTrashed()
                ->where('slug', $slug)
                ->when($ignoreId, fn ($q) => $q->whereKeyNot($ignoreId))
                ->exists()
        ) {
            $slug = $base.'-'.$suffix;
            $suffix++;
        }

        return $slug;
    }
}
