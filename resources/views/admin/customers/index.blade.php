<x-layouts.app title="Data Customer">
    <div class="flex flex-wrap items-center justify-between gap-3">
        <h1 class="text-xl font-medium">Data Customer</h1>

        <a href="{{ route('admin.customers.create') }}"
           class="rounded-lg bg-gray-900 px-4 py-2 text-sm font-medium text-white hover:bg-gray-800">
            Tambah Customer
        </a>
    </div>

    <form method="GET" class="mt-4 flex gap-2">
        <input type="search" name="search" value="{{ $search }}"
               placeholder="Cari nama atau email"
               class="w-full max-w-xs rounded-lg border border-gray-300 px-3 py-2 text-sm">
        <button type="submit"
                class="rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm hover:bg-gray-50">
            Cari
        </button>
    </form>

    <div class="mt-4 overflow-x-auto rounded-lg border border-gray-200 bg-white">
        <table class="w-full text-left text-sm">
            <thead class="border-b border-gray-200 bg-gray-50 text-gray-600">
                <tr>
                    <th class="px-4 py-3 font-medium">Nama</th>
                    <th class="hidden px-4 py-3 font-medium md:table-cell">Telepon</th>
                    <th class="px-4 py-3 font-medium">Status</th>
                    <th class="hidden px-4 py-3 font-medium lg:table-cell">Akses aktif</th>
                    <th class="px-4 py-3 text-right font-medium">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @forelse ($customers as $customer)
                    <tr>
                        <td class="px-4 py-3">
                            <div class="font-medium">{{ $customer->name }}</div>
                            <div class="text-gray-600">{{ $customer->email }}</div>
                        </td>
                        <td class="hidden px-4 py-3 md:table-cell">
                            {{ $customer->phone ?: '-' }}
                        </td>
                        <td class="px-4 py-3">
                            @if ($customer->is_active)
                                <span class="rounded-lg bg-emerald-100 px-2 py-1 text-xs text-emerald-800">Aktif</span>
                            @else
                                <span class="rounded-lg bg-gray-200 px-2 py-1 text-xs text-gray-700">Nonaktif</span>
                            @endif
                        </td>
                        <td class="hidden px-4 py-3 lg:table-cell">
                            {{ $customer->active_access_count }}
                            @if ($customer->pending_request_count > 0)
                                <span class="ml-1 rounded-lg bg-amber-100 px-2 py-1 text-xs text-amber-800">
                                    {{ $customer->pending_request_count }} request
                                </span>
                            @endif
                        </td>
                        <td class="px-4 py-3">
                            <div class="flex justify-end gap-2">
                                <a href="{{ route('admin.customers.edit', $customer) }}"
                                   class="rounded-lg border border-gray-300 px-3 py-1.5 hover:bg-gray-50">
                                    Ubah
                                </a>

                                <form method="POST" action="{{ route('admin.customers.destroy', $customer) }}"
                                      onsubmit="return confirm('Hapus customer {{ $customer->name }}?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                            class="rounded-lg border border-rose-300 px-3 py-1.5 text-rose-700 hover:bg-rose-50">
                                        Hapus
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-4 py-10 text-center text-gray-600">
                            {{ $search !== '' ? 'Tidak ada customer yang cocok dengan pencarian.' : 'Belum ada data customer.' }}
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $customers->links() }}
    </div>
</x-layouts.app>