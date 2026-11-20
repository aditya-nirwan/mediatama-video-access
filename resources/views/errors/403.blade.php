<x-layouts.guest>
    <div class="rounded-lg border border-gray-200 bg-white p-6 text-center">
        <p class="text-sm font-medium text-rose-700">403</p>
        <h1 class="mt-2 text-lg font-medium">Akses ditolak</h1>

        <p class="mt-2 text-sm text-gray-600">
            Anda tidak memiliki izin untuk membuka halaman ini. Jika masa akses video Anda
            sudah habis, ajukan akses ulang dari daftar video.
        </p>

        @auth
            <a href="{{ route('home') }}"
               class="mt-5 inline-block rounded-lg bg-gray-900 px-4 py-2 text-sm font-medium text-white hover:bg-gray-800">
                Kembali ke beranda
            </a>
        @else
            <a href="{{ route('login') }}"
               class="mt-5 inline-block rounded-lg bg-gray-900 px-4 py-2 text-sm font-medium text-white hover:bg-gray-800">
                Masuk
            </a>
        @endauth
    </div>
</x-layouts.guest>