<?php

namespace App\Http\Controllers\Admin;

use App\Enums\UserRole;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreCustomerRequest;
use App\Http\Requests\Admin\UpdateCustomerRequest;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CustomerController extends Controller
{
    public function index(Request $request): View
    {
        $search = trim((string) $request->query('search', ''));

        $customers = User::customers()
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($inner) use ($search) {
                    $inner->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
                });
            })
            ->withCount([
                'videoAccesses as active_access_count' => fn ($q) => $q->active(),
                'accessRequests as pending_request_count' => fn ($q) => $q->pending(),
            ])
            ->orderBy('name')
            ->paginate(10)
            ->withQueryString();

        return view('admin.customers.index', compact('customers', 'search'));
    }

    public function create(): View
    {
        return view('admin.customers.create');
    }

    public function store(StoreCustomerRequest $request): RedirectResponse
    {
        $customer = new User($request->validated());
        $customer->role = UserRole::Customer;
        $customer->is_active = $request->boolean('is_active');
        $customer->save();

        return redirect()
            ->route('admin.customers.index')
            ->with('status', "Customer {$customer->name} berhasil ditambahkan.");
    }

    public function edit(User $customer): View
    {
        $this->ensureCustomer($customer);

        return view('admin.customers.edit', compact('customer'));
    }

    public function update(UpdateCustomerRequest $request, User $customer): RedirectResponse
    {
        $this->ensureCustomer($customer);

        $data = $request->validated();

        if (blank($data['password'] ?? null)) {
            unset($data['password']);
        }

        $customer->fill($data);
        $customer->is_active = $request->boolean('is_active');
        $customer->save();

        return redirect()
            ->route('admin.customers.index')
            ->with('status', "Data {$customer->name} berhasil diperbarui.");
    }

    public function destroy(User $customer): RedirectResponse
    {
        $this->ensureCustomer($customer);

        $name = $customer->name;
        $customer->delete();

        return redirect()
            ->route('admin.customers.index')
            ->with('status', "Customer {$name} berhasil dihapus.");
    }

    private function ensureCustomer(User $user): void
    {
        abort_unless($user->role === UserRole::Customer, 404);
    }
}
