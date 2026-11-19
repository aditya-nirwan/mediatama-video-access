<x-layouts.app title="Dashboard Admin">
    <h1 class="text-xl font-medium">Dashboard Admin</h1>

    <div class="mt-4 grid grid-cols-2 gap-3 lg:grid-cols-4">
        @foreach ([
            'Customer' => $customerCount,
            'Video' => $videoCount,
            'Request menunggu' => $pendingCount,
            'Akses aktif' => $activeAccessCount,
        ] as $label => $value)
            <div class="rounded-lg border border-gray-200 bg-white p-4">
                <p class="text-sm text-gray-600">{{ $label }}</p>
                <p class="mt-1 text-2xl font-medium">{{ $value }}</p>
            </div>
        @endforeach
    </div>
</x-layouts.app>