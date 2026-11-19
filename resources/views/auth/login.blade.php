<x-layouts.guest>
    <div class="rounded-lg border border-gray-200 bg-white p-6">
        <h1 class="text-lg font-medium">Masuk</h1>
        <p class="mt-1 text-sm text-gray-600">Sistem perijinan menonton video</p>

        @if ($errors->any())
            <div class="mt-4 rounded-lg border border-rose-200 bg-rose-50 px-4 py-3 text-sm text-rose-800">
                {{ $errors->first() }}
            </div>
        @endif

        <form method="POST" action="{{ route('login') }}" class="mt-5 space-y-4">
            @csrf

            <div>
                <label for="email" class="block text-sm font-medium">Email</label>
                <input id="email" name="email" type="email" value="{{ old('email') }}"
                       required autofocus autocomplete="username"
                       class="mt-1 w-full rounded-lg border border-gray-300 px-3 py-2 text-sm">
            </div>

            <div>
                <label for="password" class="block text-sm font-medium">Password</label>
                <input id="password" name="password" type="password"
                       required autocomplete="current-password"
                       class="mt-1 w-full rounded-lg border border-gray-300 px-3 py-2 text-sm">
            </div>

            <label class="flex items-center gap-2 text-sm text-gray-600">
                <input type="checkbox" name="remember" class="rounded border-gray-300">
                Ingat saya
            </label>

            <button type="submit"
                    class="w-full rounded-lg bg-gray-900 px-4 py-2 text-sm font-medium text-white hover:bg-gray-800">
                Masuk
            </button>
        </form>
    </div>
</x-layouts.guest>