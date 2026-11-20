@props(['withCustom' => true])

<fieldset>
    <legend class="text-sm font-medium">Masa berlaku</legend>

    <div class="mt-2 grid grid-cols-2 gap-2 text-sm">
        @foreach (App\Support\AccessDuration::OPTIONS as $key => $option)
            <label class="flex items-center gap-2 rounded-lg border border-gray-300 px-3 py-2">
                <input type="radio" name="duration" value="{{ $key }}" @checked($key === '1d')>
                {{ $option['label'] }}
            </label>
        @endforeach

        @if ($withCustom)
            <label class="flex items-center gap-2 rounded-lg border border-gray-300 px-3 py-2">
                <input type="radio" name="duration" value="custom">
                Custom
            </label>
        @endif
    </div>

    @if ($withCustom)
        <div class="mt-3">
            <label class="block text-sm text-gray-600">Waktu berakhir (untuk pilihan custom)</label>
            <input type="datetime-local" name="expires_at"
                   class="mt-1 w-full rounded-lg border border-gray-300 px-3 py-2 text-sm">
        </div>
    @endif
</fieldset>