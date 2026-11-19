@props(['title' => null])
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? config('app.name') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-gray-100 text-gray-900 antialiased">
        <nav class="border-b border-gray-200 bg-white">
        <div class="mx-auto max-w-6xl px-4">
            <div class="flex items-center justify-between gap-4 py-3">
                <a href="{{ route('home') }}" class="text-sm font-medium">
                    {{ config('app.name') }}
                </a>

                <div class="flex items-center gap-3">
                    <span class="hidden text-sm text-gray-600 sm:inline">
                        {{ auth()->user()->name }}
                    </span>

                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit"
                                class="rounded-lg border border-gray-300 px-3 py-1.5 text-sm hover:bg-gray-50">
                            Keluar
                        </button>
                    </form>
                </div>
            </div>

            @if (auth()->user()->isAdmin())
                <div class="flex gap-1 overflow-x-auto pb-2 text-sm">
                    @foreach ([
                        'admin.dashboard' => 'Dashboard',
                        'admin.customers.index' => 'Customer',
                    ] as $route => $label)
                        <a href="{{ route($route) }}"
                           class="whitespace-nowrap rounded-lg px-3 py-1.5 {{ request()->routeIs(Str::beforeLast($route, '.').'.*') ? 'bg-gray-900 text-white' : 'text-gray-600 hover:bg-gray-100' }}">
                            {{ $label }}
                        </a>
                    @endforeach
                </div>
            @endif
        </div>
    </nav>

    <main class="mx-auto max-w-6xl px-4 py-6">
        @if (session('status'))
            <div class="mb-4 rounded-lg border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-800">
                {{ session('status') }}
            </div>
        @endif

        {{ $slot }}
    </main>
</body>
</html>