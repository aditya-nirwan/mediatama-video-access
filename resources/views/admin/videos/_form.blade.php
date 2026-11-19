@csrf

<div class="space-y-4">
    <div>
        <label for="title" class="block text-sm font-medium">Judul</label>
        <input id="title" name="title" type="text" required
               value="{{ old('title', $video->title ?? '') }}"
               class="mt-1 w-full rounded-lg border border-gray-300 px-3 py-2 text-sm">
        @error('title') <p class="mt-1 text-sm text-rose-700">{{ $message }}</p> @enderror
    </div>

    <div>
        <label for="description" class="block text-sm font-medium">Deskripsi</label>
        <textarea id="description" name="description" rows="3"
                  class="mt-1 w-full rounded-lg border border-gray-300 px-3 py-2 text-sm">{{ old('description', $video->description ?? '') }}</textarea>
        @error('description') <p class="mt-1 text-sm text-rose-700">{{ $message }}</p> @enderror
    </div>

    <div>
        <label for="file" class="block text-sm font-medium">
            File video {{ isset($video) ? '(kosongkan jika tidak diganti)' : '' }}
        </label>
        <input id="file" name="file" type="file" accept="video/mp4,video/webm"
               @required(! isset($video))
               class="mt-1 w-full rounded-lg border border-gray-300 px-3 py-2 text-sm">
        <p class="mt-1 text-sm text-gray-600">MP4 atau WebM, maksimal 64 MB.</p>
        @error('file') <p class="mt-1 text-sm text-rose-700">{{ $message }}</p> @enderror

        @isset($video)
            <p class="mt-1 text-sm text-gray-600">
                Saat ini: {{ $video->original_name }}
                ({{ number_format($video->size_bytes / 1048576, 1) }} MB)
            </p>
        @endisset
    </div>

    <div class="grid gap-4 sm:grid-cols-2">
        <div>
            <label for="thumbnail" class="block text-sm font-medium">Thumbnail</label>
            <input id="thumbnail" name="thumbnail" type="file" accept="image/*"
                   class="mt-1 w-full rounded-lg border border-gray-300 px-3 py-2 text-sm">
            @error('thumbnail') <p class="mt-1 text-sm text-rose-700">{{ $message }}</p> @enderror
        </div>

        <div>
            <label for="duration_seconds" class="block text-sm font-medium">Durasi (detik)</label>
            <input id="duration_seconds" name="duration_seconds" type="number" min="1"
                   value="{{ old('duration_seconds', $video->duration_seconds ?? '') }}"
                   class="mt-1 w-full rounded-lg border border-gray-300 px-3 py-2 text-sm">
            @error('duration_seconds') <p class="mt-1 text-sm text-rose-700">{{ $message }}</p> @enderror
        </div>
    </div>

    <label class="flex items-center gap-2 text-sm">
        <input type="checkbox" name="is_published" value="1"
               @checked(old('is_published', $video->is_published ?? true))
               class="rounded border-gray-300">
        Tampilkan video ini kepada customer
    </label>
</div>