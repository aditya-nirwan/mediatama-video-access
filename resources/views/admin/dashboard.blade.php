<x-layouts.app title="Dashboard Admin">
    <h1 class="text-xl font-medium">Dashboard Admin</h1>

    <div class="mt-4 grid grid-cols-2 gap-3 lg:grid-cols-4">
        @foreach ([
            ['label' => 'Customer', 'value' => $customerCount, 'route' => 'admin.customers.index'],
            ['label' => 'Video', 'value' => $videoCount, 'route' => 'admin.videos.index'],
            ['label' => 'Request menunggu', 'value' => $pendingCount, 'route' => 'admin.requests.index'],
            ['label' => 'Akses aktif', 'value' => $activeAccessCount, 'route' => 'admin.accesses.index'],
        ] as $card)
            <a href="{{ route($card['route']) }}"
               class="rounded-lg border border-gray-200 bg-white p-4 hover:border-gray-400">
                <p class="text-sm text-gray-600">{{ $card['label'] }}</p>
                <p class="mt-1 text-2xl font-medium">{{ $card['value'] }}</p>
            </a>
        @endforeach
    </div>
</x-layouts.app>