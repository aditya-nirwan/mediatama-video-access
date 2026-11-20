@props(['id', 'title'])

<dialog id="{{ $id }}"
        class="m-auto max-h-[calc(100vh-4rem)] w-[calc(100%-2rem)] max-w-md overflow-y-auto rounded-lg border border-gray-200 bg-white p-0">
    <div class="flex items-center justify-between border-b border-gray-200 px-4 py-3">
        <h2 class="font-medium">{{ $title }}</h2>
        <button type="button" onclick="this.closest('dialog').close()"
                class="rounded-lg px-2 py-1 text-sm text-gray-600 hover:bg-gray-100">
            Tutup
        </button>
    </div>

    <div class="px-4 py-4">
        {{ $slot }}
    </div>
</dialog>