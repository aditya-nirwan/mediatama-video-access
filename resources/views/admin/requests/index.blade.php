<x-layouts.app title="Permintaan Akses">
    <h1 class="text-xl font-medium">Permintaan Akses</h1>

    @if ($errors->any())
        <div class="mt-4 rounded-lg border border-rose-200 bg-rose-50 px-4 py-3 text-sm text-rose-800">
            {{ $errors->first() }}
        </div>
    @endif

    <h2 class="mt-6 font-medium">Menunggu keputusan ({{ $pending->count() }})</h2>

    <div class="mt-3 space-y-3">
        @forelse ($pending as $item)
            <div class="rounded-lg border border-gray-200 bg-white p-4">
                <div class="flex flex-wrap items-start justify-between gap-3">
                    <div class="min-w-0">
                        <p class="font-medium">{{ $item->user->name }}</p>
                        <p class="text-sm text-gray-600">{{ $item->video->title }}</p>
                        <p class="mt-1 text-sm text-gray-600">
                            Diajukan {{ $item->created_at->diffForHumans() }}
                        </p>
                        @if ($item->reason)
                            <p class="mt-2 text-sm text-gray-700">Alasan: {{ $item->reason }}</p>
                        @endif
                    </div>

                    <div class="flex gap-2 text-sm">
                        <button type="button"
                                onclick="document.getElementById('approve-{{ $item->id }}').showModal()"
                                class="rounded-lg bg-gray-900 px-3 py-1.5 font-medium text-white hover:bg-gray-800">
                            Setujui
                        </button>
                        <button type="button"
                                onclick="document.getElementById('reject-{{ $item->id }}').showModal()"
                                class="rounded-lg border border-rose-300 px-3 py-1.5 text-rose-700 hover:bg-rose-50">
                            Tolak
                        </button>
                    </div>
                </div>
            </div>

            <x-modal id="approve-{{ $item->id }}" title="Setujui akses">
                <form method="POST" action="{{ route('admin.requests.approve', $item) }}">
                    @csrf
                    <p class="text-sm text-gray-600">
                        {{ $item->user->name }} akan dapat menonton {{ $item->video->title }}.
                    </p>

                    <div class="mt-4">
                        <x-duration-picker />
                    </div>

                    <div class="mt-4">
                        <label class="block text-sm font-medium">Catatan (opsional)</label>
                        <textarea name="admin_note" rows="2"
                                  class="mt-1 w-full rounded-lg border border-gray-300 px-3 py-2 text-sm"></textarea>
                    </div>

                    <button type="submit"
                            class="mt-4 w-full rounded-lg bg-gray-900 px-4 py-2 text-sm font-medium text-white hover:bg-gray-800">
                        Berikan Akses
                    </button>
                </form>
            </x-modal>

            <x-modal id="reject-{{ $item->id }}" title="Tolak permintaan">
                <form method="POST" action="{{ route('admin.requests.reject', $item) }}">
                    @csrf
                    <label class="block text-sm font-medium">Alasan penolakan (opsional)</label>
                    <textarea name="admin_note" rows="3"
                              class="mt-1 w-full rounded-lg border border-gray-300 px-3 py-2 text-sm"></textarea>

                    <button type="submit"
                            class="mt-4 w-full rounded-lg bg-rose-700 px-4 py-2 text-sm font-medium text-white hover:bg-rose-800">
                        Tolak Permintaan
                    </button>
                </form>
            </x-modal>
        @empty
            <div class="rounded-lg border border-gray-200 bg-white px-4 py-10 text-center text-gray-600">
                Tidak ada permintaan yang menunggu.
            </div>
        @endforelse
    </div>

    <h2 class="mt-8 font-medium">Riwayat</h2>

    <div class="mt-3 overflow-x-auto rounded-lg border border-gray-200 bg-white">
        <table class="w-full text-left text-sm">
            <thead class="border-b border-gray-200 bg-gray-50 text-gray-600">
                <tr>
                    <th class="px-4 py-3 font-medium">Customer</th>
                    <th class="hidden px-4 py-3 font-medium md:table-cell">Video</th>
                    <th class="px-4 py-3 font-medium">Status</th>
                    <th class="hidden px-4 py-3 font-medium lg:table-cell">Diputuskan</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @forelse ($processed as $item)
                    <tr>
                        <td class="px-4 py-3">{{ $item->user->name }}</td>
                        <td class="hidden px-4 py-3 md:table-cell">{{ $item->video->title }}</td>
                        <td class="px-4 py-3">
                            <x-badge :color="$item->status->badgeColor()">
                                {{ $item->status->label() }}
                            </x-badge>
                        </td>
                        <td class="hidden px-4 py-3 lg:table-cell">
                            {{ $item->responded_at?->translatedFormat('d M Y H:i') ?? '-' }}
                            @if ($item->responder)
                                <span class="text-gray-600">oleh {{ $item->responder->name }}</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="px-4 py-10 text-center text-gray-600">
                            Belum ada permintaan yang diproses.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">{{ $processed->links() }}</div>
</x-layouts.app>