<x-layouts.app title="Dashboard Customer">
    <h1 class="text-xl font-medium">Halo, {{ auth()->user()->name }}</h1>

    <div class="mt-4 grid grid-cols-2 gap-3">
        <div class="rounded-lg border border-gray-200 bg-white p-4">
            <p class="text-sm text-gray-600">Akses aktif</p>
            <p class="mt-1 text-2xl font-medium">{{ $activeCount }}</p>
        </div>
        <div class="rounded-lg border border-gray-200 bg-white p-4">
            <p class="text-sm text-gray-600">Request menunggu</p>
            <p class="mt-1 text-2xl font-medium">{{ $pendingCount }}</p>
        </div>
    </div>
</x-layouts.app>