<x-layouts.guest>
    <div class="rounded-lg border border-gray-200 bg-white p-6 text-center">
        <p class="text-sm font-medium text-gray-600">404</p>
        <h1 class="mt-2 text-lg font-medium">Halaman tidak ditemukan</h1>

        <p class="mt-2 text-sm text-gray-600">
            Alamat yang Anda buka tidak tersedia atau datanya sudah dihapus.
        </p>

        <a href="{{ route('home') }}"
           class="mt-5 inline-block rounded-lg bg-gray-900 px-4 py-2 text-sm font-medium text-white hover:bg-gray-800">
            Kembali ke beranda
        </a>
    </div>
</x-layouts.guest>