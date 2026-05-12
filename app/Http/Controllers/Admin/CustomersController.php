<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCustomerRequest;
use App\Http\Requests\UpdateCustomerRequest;
use App\Models\Role;
use App\Models\User;
use Gate;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;

class CustomersController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('customer_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $customers = User::with(['addresses', 'defaultAddress'])
            ->whereHas('roles', function ($query) {
                $query->where('title', 'Customer');
            })
            ->latest()
            ->get();

        return view('admin.customers.index', compact('customers'));
    }

    public function create()
    {
        abort_if(Gate::denies('customer_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.customers.create');
    }

    public function store(StoreCustomerRequest $request)
    {
        $data = $request->validated();

        $data['status'] = $request->has('status') ? 1 : 0;
        $data['email'] = ($data['email'] ?? null) ?: $this->makePlaceholderEmail();
        $data['password'] = ($data['password'] ?? null) ?: Str::random(16);

        $user = User::create($data);

        $customerRole = Role::where('title', 'Customer')->first();
        if ($customerRole) {
            $user->roles()->syncWithoutDetaching([$customerRole->id]);
        }

        return redirect()
            ->route('admin.customers.index')
            ->with('message', 'Customer created successfully.');
    }

    public function show(User $customer)
    {
        abort_if(Gate::denies('customer_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        abort_unless($this->isCustomer($customer), Response::HTTP_NOT_FOUND);

        $customer->load(['addresses', 'defaultAddress']);

        return view('admin.customers.show', compact('customer'));
    }

    public function edit(User $customer)
    {
        abort_if(Gate::denies('customer_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        abort_unless($this->isCustomer($customer), Response::HTTP_NOT_FOUND);

        $customer->load(['addresses']);

        return view('admin.customers.edit', compact('customer'));
    }

    public function update(UpdateCustomerRequest $request, User $customer)
    {
        abort_unless($this->isCustomer($customer), Response::HTTP_NOT_FOUND);

        $data = $request->validated();

        if (! $request->filled('password')) {
            unset($data['password']);
        }

        $data['status'] = $request->has('status') ? 1 : 0;
        $data['email'] = ($data['email'] ?? null) ?: $this->makePlaceholderEmail();

        $customer->update($data);

        return redirect()
            ->route('admin.customers.index')
            ->with('message', 'Customer updated successfully.');
    }

    public function destroy(User $customer)
    {
        abort_if(Gate::denies('customer_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        abort_unless($this->isCustomer($customer), Response::HTTP_NOT_FOUND);

        $customer->delete();

        return back()->with('message', 'Customer deleted successfully.');
    }

    public function massDestroy(Request $request)
    {
        abort_if(Gate::denies('customer_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        User::whereIn('id', request('ids', []))
            ->whereHas('roles', function ($query) {
                $query->where('title', 'Customer');
            })
            ->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }

    private function isCustomer(User $user): bool
    {
        return $user->roles()->where('title', 'Customer')->exists();
    }

    private function makePlaceholderEmail(): string
    {
        do {
            $email = 'customer-' . Str::lower(Str::random(12)) . '@local.invalid';
        } while (User::where('email', $email)->exists());

        return $email;
    }
}
