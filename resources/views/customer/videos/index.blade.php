<x-layouts.app title="Daftar Video">
    <h1 class="text-xl font-medium">Daftar Video</h1>
    <p class="mt-1 text-sm text-gray-600">
        Ajukan akses ke admin untuk video yang ingin Anda tonton.
    </p>

    @if ($errors->any())
        <div class="mt-4 rounded-lg border border-rose-200 bg-rose-50 px-4 py-3 text-sm text-rose-800">
            {{ $errors->first() }}
        </div>
    @endif

    <div class="mt-4 grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
        @forelse ($videos as $video)
            @php($state = $states[$video->id])

            <div class="flex flex-col rounded-lg border border-gray-200 bg-white">
                <div class="flex aspect-video items-center justify-center rounded-t-lg bg-gray-100">
                    @if ($video->thumbnail_path)
                        <img src="{{ Storage::disk('public')->url($video->thumbnail_path) }}"
                             alt="" class="aspect-video w-full rounded-t-lg object-cover">
                    @else
                        <span class="text-sm text-gray-500">Tanpa thumbnail</span>
                    @endif
                </div>

                <div class="flex flex-1 flex-col p-4">
                    <div class="flex items-start justify-between gap-2">
                        <h2 class="font-medium">{{ $video->title }}</h2>
                        <x-badge :color="$state->badgeColor()" class="shrink-0">
                            {{ $state->label() }}
                        </x-badge>
                    </div>

                    @if ($video->description)
                        <p class="mt-2 text-sm text-gray-600">{{ Str::limit($video->description, 90) }}</p>
                    @endif

                    @if ($state->isWatchable())
                        <p class="mt-2 text-sm text-gray-600">
                            Berlaku sampai {{ $state->access->expires_at->translatedFormat('d M Y H:i') }}
                        </p>
                    @elseif ($state->isRejected() && $state->request->admin_note)
                        <p class="mt-2 text-sm text-gray-600">
                            Catatan admin: {{ $state->request->admin_note }}
                        </p>
                    @endif

                    <div class="mt-4 flex-1"></div>

                    @if ($state->isWatchable())
                        <a href="{{ route('videos.watch', $video) }}"
                           class="rounded-lg bg-gray-900 px-4 py-2 text-center text-sm font-medium text-white hover:bg-gray-800">
                            Tonton
                        </a>
                    @elseif ($state->isPending())
                        <button type="button" disabled
                                class="rounded-lg border border-gray-300 px-4 py-2 text-sm text-gray-500">
                            Menunggu persetujuan
                        </button>
                    @else
                        <details class="group">
                            <summary class="cursor-pointer list-none rounded-lg border border-gray-300 px-4 py-2 text-center text-sm hover:bg-gray-50">
                                {{ $state->access ? 'Ajukan Akses Ulang' : 'Ajukan Akses' }}
                            </summary>

                            <form method="POST" action="{{ route('customer.videos.request', $video) }}" class="mt-3">
                                @csrf
                                <label for="reason-{{ $video->id }}" class="block text-sm font-medium">
                                    Alasan (opsional)
                                </label>
                                <textarea id="reason-{{ $video->id }}" name="reason" rows="2"
                                          class="mt-1 w-full rounded-lg border border-gray-300 px-3 py-2 text-sm"></textarea>
                                <button type="submit"
                                        class="mt-2 w-full rounded-lg bg-gray-900 px-4 py-2 text-sm font-medium text-white hover:bg-gray-800">
                                    Kirim Permintaan
                                </button>
                            </form>
                        </details>
                    @endif
                </div>
            </div>
        @empty
            <div class="rounded-lg border border-gray-200 bg-white px-4 py-10 text-center text-gray-600 sm:col-span-2 lg:col-span-3">
                Belum ada video yang tersedia.
            </div>
        @endforelse
    </div>

    <div class="mt-4">
        {{ $videos->links() }}
    </div>
</x-layouts.app>