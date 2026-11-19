<x-layouts.app title="Tambah Customer">
    <h1 class="text-xl font-medium">Tambah Customer</h1>

    <form method="POST" action="{{ route('admin.customers.store') }}"
          class="mt-4 max-w-xl rounded-lg border border-gray-200 bg-white p-6">
        @include('admin.customers._form')

        <div class="mt-6 flex gap-2">
            <button type="submit"
                    class="rounded-lg bg-gray-900 px-4 py-2 text-sm font-medium text-white hover:bg-gray-800">
                Simpan
            </button>
            <a href="{{ route('admin.customers.index') }}"
               class="rounded-lg border border-gray-300 px-4 py-2 text-sm hover:bg-gray-50">
                Batal
            </a>
        </div>
    </form>
</x-layouts.app>