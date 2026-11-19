<x-layouts.app title="Ubah Video">
    <h1 class="text-xl font-medium">Ubah Video</h1>

    <form method="POST" action="{{ route('admin.videos.update', $video) }}" enctype="multipart/form-data"
          class="mt-4 max-w-xl rounded-lg border border-gray-200 bg-white p-6">
        @method('PUT')
        @include('admin.videos._form')

        <div class="mt-6 flex gap-2">
            <button type="submit"
                    class="rounded-lg bg-gray-900 px-4 py-2 text-sm font-medium text-white hover:bg-gray-800">
                Simpan perubahan
            </button>
            <a href="{{ route('admin.videos.index') }}"
               class="rounded-lg border border-gray-300 px-4 py-2 text-sm hover:bg-gray-50">
                Batal
            </a>
        </div>
    </form>
</x-layouts.app>