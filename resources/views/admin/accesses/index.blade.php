<x-layouts.app title="Akses Video">
    <div class="flex flex-wrap items-center justify-between gap-3">
        <h1 class="text-xl font-medium">Akses Video</h1>

        <button type="button" onclick="document.getElementById('grant-direct').showModal()"
                class="rounded-lg bg-gray-900 px-4 py-2 text-sm font-medium text-white hover:bg-gray-800">
            Beri Akses Langsung
        </button>
    </div>

    @if ($errors->any())
        <div class="mt-4 rounded-lg border border-rose-200 bg-rose-50 px-4 py-3 text-sm text-rose-800">
            {{ $errors->first() }}
        </div>
    @endif

    <div class="mt-4 flex gap-1 overflow-x-auto text-sm">
        @foreach (['active' => 'Aktif', 'expired' => 'Kedaluwarsa', 'revoked' => 'Dicabut', 'all' => 'Semua'] as $key => $label)
            <a href="{{ route('admin.accesses.index', ['filter' => $key]) }}"
               class="whitespace-nowrap rounded-lg px-3 py-1.5 {{ $filter === $key ? 'bg-gray-900 text-white' : 'border border-gray-300 bg-white text-gray-600 hover:bg-gray-50' }}">
                {{ $label }}
            </a>
        @endforeach
    </div>

    <div class="mt-4 space-y-3">
        @forelse ($accesses as $access)
            <div class="rounded-lg border border-gray-200 bg-white p-4">
                <div class="flex flex-wrap items-start justify-between gap-3">
                    <div class="min-w-0">
                        <p class="font-medium">{{ $access->user->name }}</p>
                        <p class="text-sm text-gray-600">{{ $access->video->title }}</p>
                        <p class="mt-1 text-sm text-gray-600">
                            {{ $access->starts_at->translatedFormat('d M Y H:i') }}
                            sampai {{ $access->expires_at->translatedFormat('d M Y H:i') }}
                        </p>
                        <p class="mt-1 text-sm text-gray-600">
                            Diberikan oleh {{ $access->granter->name }}
                            &middot; {{ $access->access_request_id ? 'dari permintaan customer' : 'langsung oleh admin' }}
                        </p>
                        @if ($access->watchLog)
                            <p class="mt-1 text-sm text-gray-600">
                                Ditonton {{ $access->watchLog->watch_count }} kali
                                &middot; terakhir {{ $access->watchLog->last_watched_at->diffForHumans() }}
                                &middot; posisi {{ gmdate('i:s', $access->watchLog->last_position_seconds) }}
                            </p>
                        @else
                            <p class="mt-1 text-sm text-gray-500">Belum pernah ditonton</p>
                        @endif                        
                    </div>

                    <div class="flex flex-col items-end gap-2">
                        @if ($access->isRevoked())
                            <x-badge color="red">Dicabut</x-badge>
                        @elseif ($access->isActive())
                            <x-badge color="green">Aktif</x-badge>
                        @else
                            <x-badge color="red">Kedaluwarsa</x-badge>
                        @endif

                        @unless ($access->isRevoked())
                            <div class="flex gap-2 text-sm">
                                <button type="button"
                                        onclick="document.getElementById('extend-{{ $access->id }}').showModal()"
                                        class="rounded-lg border border-gray-300 px-3 py-1.5 hover:bg-gray-50">
                                    Perpanjang
                                </button>

                                <form method="POST" action="{{ route('admin.accesses.destroy', $access) }}"
                                      onsubmit="return confirm('Cabut akses {{ $access->user->name }}?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                            class="rounded-lg border border-rose-300 px-3 py-1.5 text-rose-700 hover:bg-rose-50">
                                        Cabut
                                    </button>
                                </form>
                            </div>
                        @endunless
                    </div>
                </div>
            </div>

            @unless ($access->isRevoked())
                <x-modal id="extend-{{ $access->id }}" title="Perpanjang akses">
                    <form method="POST" action="{{ route('admin.accesses.extend', $access) }}">
                        @csrf
                        <p class="text-sm text-gray-600">
                            Durasi ditambahkan ke akhir masa berlaku yang sekarang.
                        </p>

                        <div class="mt-4">
                            <x-duration-picker :with-custom="false" />
                        </div>

                        <button type="submit"
                                class="mt-4 w-full rounded-lg bg-gray-900 px-4 py-2 text-sm font-medium text-white hover:bg-gray-800">
                            Perpanjang
                        </button>
                    </form>
                </x-modal>
            @endunless
        @empty
            <div class="rounded-lg border border-gray-200 bg-white px-4 py-10 text-center text-gray-600">
                Tidak ada data akses pada filter ini.
            </div>
        @endforelse
    </div>

    <div class="mt-4">{{ $accesses->links() }}</div>

    <x-modal id="grant-direct" title="Beri akses langsung">
        <form method="POST" action="{{ route('admin.accesses.store') }}">
            @csrf

            <div>
                <label for="user_id" class="block text-sm font-medium">Customer</label>
                <select id="user_id" name="user_id" required
                        class="mt-1 w-full rounded-lg border border-gray-300 px-3 py-2 text-sm">
                    @foreach ($customers as $customer)
                        <option value="{{ $customer->id }}">{{ $customer->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="mt-4">
                <label for="video_id" class="block text-sm font-medium">Video</label>
                <select id="video_id" name="video_id" required
                        class="mt-1 w-full rounded-lg border border-gray-300 px-3 py-2 text-sm">
                    @foreach ($videos as $video)
                        <option value="{{ $video->id }}">{{ $video->title }}</option>
                    @endforeach
                </select>
            </div>

            <div class="mt-4">
                <x-duration-picker />
            </div>

            <button type="submit"
                    class="mt-4 w-full rounded-lg bg-gray-900 px-4 py-2 text-sm font-medium text-white hover:bg-gray-800">
                Berikan Akses
            </button>
        </form>
    </x-modal>
</x-layouts.app>