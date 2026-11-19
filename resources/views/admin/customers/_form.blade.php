@csrf

<div class="space-y-4">
    <div>
        <label for="name" class="block text-sm font-medium">Nama</label>
        <input id="name" name="name" type="text" required
               value="{{ old('name', $customer->name ?? '') }}"
               class="mt-1 w-full rounded-lg border border-gray-300 px-3 py-2 text-sm">
        @error('name') <p class="mt-1 text-sm text-rose-700">{{ $message }}</p> @enderror
    </div>

    <div>
        <label for="email" class="block text-sm font-medium">Email</label>
        <input id="email" name="email" type="email" required
               value="{{ old('email', $customer->email ?? '') }}"
               class="mt-1 w-full rounded-lg border border-gray-300 px-3 py-2 text-sm">
        @error('email') <p class="mt-1 text-sm text-rose-700">{{ $message }}</p> @enderror
    </div>

    <div class="grid gap-4 sm:grid-cols-2">
        <div>
            <label for="password" class="block text-sm font-medium">
                Password {{ isset($customer) ? '(kosongkan jika tidak diubah)' : '' }}
            </label>
            <input id="password" name="password" type="password"
                   autocomplete="new-password"
                   class="mt-1 w-full rounded-lg border border-gray-300 px-3 py-2 text-sm">
            @error('password') <p class="mt-1 text-sm text-rose-700">{{ $message }}</p> @enderror
        </div>

        <div>
            <label for="password_confirmation" class="block text-sm font-medium">Ulangi password</label>
            <input id="password_confirmation" name="password_confirmation" type="password"
                   autocomplete="new-password"
                   class="mt-1 w-full rounded-lg border border-gray-300 px-3 py-2 text-sm">
        </div>
    </div>

    <div>
        <label for="phone" class="block text-sm font-medium">Telepon</label>
        <input id="phone" name="phone" type="text"
               value="{{ old('phone', $customer->phone ?? '') }}"
               class="mt-1 w-full rounded-lg border border-gray-300 px-3 py-2 text-sm">
        @error('phone') <p class="mt-1 text-sm text-rose-700">{{ $message }}</p> @enderror
    </div>

    <div>
        <label for="address" class="block text-sm font-medium">Alamat</label>
        <textarea id="address" name="address" rows="3"
                  class="mt-1 w-full rounded-lg border border-gray-300 px-3 py-2 text-sm">{{ old('address', $customer->address ?? '') }}</textarea>
        @error('address') <p class="mt-1 text-sm text-rose-700">{{ $message }}</p> @enderror
    </div>

    <label class="flex items-center gap-2 text-sm">
        <input type="checkbox" name="is_active" value="1"
               @checked(old('is_active', $customer->is_active ?? true))
               class="rounded border-gray-300">
        Akun aktif dan boleh login
    </label>
</div>