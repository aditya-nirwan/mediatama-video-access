<x-layouts.app title="Data Video">
    <div class="flex flex-wrap items-center justify-between gap-3">
        <h1 class="text-xl font-medium">Data Video</h1>

        <a href="{{ route('admin.videos.create') }}"
           class="rounded-lg bg-gray-900 px-4 py-2 text-sm font-medium text-white hover:bg-gray-800">
            Unggah Video
        </a>
    </div>

    <form method="GET" class="mt-4 flex gap-2">
        <input type="search" name="search" value="{{ $search }}"
               placeholder="Cari judul video"
               class="w-full max-w-xs rounded-lg border border-gray-300 px-3 py-2 text-sm">
        <button type="submit"
                class="rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm hover:bg-gray-50">
            Cari
        </button>
    </form>

    <div class="mt-4 space-y-3">
        @forelse ($videos as $video)
            <div class="flex flex-col gap-4 rounded-lg border border-gray-200 bg-white p-4 sm:flex-row">
                <div class="flex h-24 w-full shrink-0 items-center justify-center rounded-lg bg-gray-100 sm:w-40">
                    @if ($video->thumbnail_path)
                        <img src="{{ Storage::disk('public')->url($video->thumbnail_path) }}"
                             alt="" class="h-24 w-full rounded-lg object-cover sm:w-40">
                    @else
                        <span class="text-sm text-gray-500">Tanpa thumbnail</span>
                    @endif
                </div>

                <div class="min-w-0 flex-1">
                    <div class="flex flex-wrap items-center gap-2">
                        <h2 class="font-medium">{{ $video->title }}</h2>

                        @unless ($video->is_published)
                            <x-badge>Disembunyikan</x-badge>
                        @endunless
                    </div>

                    <p class="mt-1 text-sm text-gray-600">
                        {{ Str::limit($video->description, 120) ?: 'Tanpa deskripsi' }}
                    </p>

                    <p class="mt-2 text-sm text-gray-600">
                        {{ number_format($video->size_bytes / 1048576, 1) }} MB
                        @if ($video->duration_seconds)
                            &middot; {{ gmdate('i:s', $video->duration_seconds) }}
                        @endif
                        &middot; {{ $video->active_access_count }} akses aktif
                        @if ($video->pending_request_count > 0)
                            &middot; <span class="text-amber-700">{{ $video->pending_request_count }} request menunggu</span>
                        @endif
                    </p>
                </div>

                <div class="flex shrink-0 gap-2 text-sm sm:flex-col">
                    <a href="{{ route('admin.videos.edit', $video) }}"
                       class="rounded-lg border border-gray-300 px-3 py-1.5 text-center hover:bg-gray-50">
                        Ubah
                    </a>

                    <form method="POST" action="{{ route('admin.videos.destroy', $video) }}"
                          onsubmit="return confirm('Hapus video {{ $video->title }}?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit"
                                class="w-full rounded-lg border border-rose-300 px-3 py-1.5 text-rose-700 hover:bg-rose-50">
                            Hapus
                        </button>
                    </form>
                </div>
            </div>
        @empty
            <div class="rounded-lg border border-gray-200 bg-white px-4 py-10 text-center text-gray-600">
                {{ $search !== '' ? 'Tidak ada video yang cocok.' : 'Belum ada video yang diunggah.' }}
            </div>
        @endforelse
    </div>

    <div class="mt-4">
        {{ $videos->links() }}
    </div>
</x-layouts.app>