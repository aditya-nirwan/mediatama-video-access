<x-layouts.app :title="$video->title">
    <h1 class="text-xl font-medium">{{ $video->title }}</h1>

    @if ($access)
        <p class="mt-1 text-sm text-gray-600">
            Akses berlaku sampai {{ $access->expires_at->translatedFormat('d F Y H:i') }}
        </p>
    @else
        <p class="mt-1 text-sm text-gray-600">Anda menonton sebagai administrator.</p>
    @endif

    <div class="mt-4 overflow-hidden rounded-lg border border-gray-200 bg-black">
        <video
            class="aspect-video w-full"
            controls
            playsinline
            preload="metadata"
            controlslist="nodownload"
            oncontextmenu="return false"
            src="{{ route('videos.stream', $video) }}">
            Browser Anda tidak mendukung pemutar video.
        </video>
    </div>

    @if ($video->description)
        <p class="mt-4 max-w-2xl text-sm text-gray-700">{{ $video->description }}</p>
    @endif
</x-layouts.app>