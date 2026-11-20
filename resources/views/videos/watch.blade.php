<x-layouts.app :title="$video->title">
    <div class="flex flex-wrap items-start justify-between gap-3">
        <div>
            <h1 class="text-xl font-medium">{{ $video->title }}</h1>

            @if ($access)
                <p class="mt-1 text-sm text-gray-600">
                    Berlaku sampai {{ $access->expires_at->translatedFormat('d M Y H:i') }}
                </p>
            @else
                <p class="mt-1 text-sm text-gray-600">Anda menonton sebagai administrator.</p>
            @endif
        </div>

        @if ($access)
            <div class="rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm">
                Sisa waktu: <span id="countdown" class="font-medium">menghitung...</span>
            </div>
        @endif
    </div>

    <div id="expired-notice"
         class="mt-4 hidden rounded-lg border border-rose-200 bg-rose-50 px-4 py-3 text-sm text-rose-800">
        Masa akses Anda sudah habis. Video dihentikan.
        <a href="{{ route('customer.videos.index') }}" class="font-medium underline">
            Ajukan akses ulang
        </a>
    </div>

    <div class="mt-4 overflow-hidden rounded-lg border border-gray-200 bg-black">
        <video
            id="player"
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

    <a href="{{ route(auth()->user()->isAdmin() ? 'admin.videos.index' : 'customer.videos.index') }}"
       class="mt-4 inline-block text-sm text-gray-600 underline">
        Kembali ke daftar video
    </a>

    @if ($access)
        <script>
            (function () {
                const video = document.getElementById('player');
                const countdown = document.getElementById('countdown');
                const notice = document.getElementById('expired-notice');
                const token = document.querySelector('meta[name="csrf-token"]').content;
                const progressUrl = @json(route('videos.progress', $video));
                const resumeAt = @json($resumeAt);
                const deadline = Date.now() + (@json($remainingSeconds) * 1000);

                let timer = null;

                function format(seconds) {
                    const days = Math.floor(seconds / 86400);
                    const hours = Math.floor((seconds % 86400) / 3600);
                    const minutes = Math.floor((seconds % 3600) / 60);
                    const secs = seconds % 60;

                    if (days > 0) return days + ' hari ' + hours + ' jam';
                    if (hours > 0) return hours + ' jam ' + minutes + ' menit';
                    if (minutes > 0) return minutes + ' menit ' + secs + ' detik';

                    return secs + ' detik';
                }

                function render() {
                    const remaining = Math.round((deadline - Date.now()) / 1000);

                    if (remaining <= 0) {
                        countdown.textContent = 'habis';
                        video.pause();
                        notice.classList.remove('hidden');
                        clearInterval(timer);
                        return;
                    }

                    countdown.textContent = format(remaining);
                }

                render();
                timer = setInterval(render, 1000);

                if (resumeAt > 0) {
                    video.addEventListener('loadedmetadata', function () {
                        if (resumeAt < video.duration - 5) {
                            video.currentTime = resumeAt;
                        }
                    }, { once: true });
                }

                function saveProgress() {
                    if (!video.currentTime) return;

                    fetch(progressUrl, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': token,
                        },
                        body: JSON.stringify({ position: Math.floor(video.currentTime) }),
                        keepalive: true,
                    }).catch(function () {});
                }

                setInterval(function () {
                    if (!video.paused) saveProgress();
                }, 10000);

                video.addEventListener('pause', saveProgress);
                window.addEventListener('pagehide', saveProgress);
            })();
        </script>
    @endif
</x-layouts.app>