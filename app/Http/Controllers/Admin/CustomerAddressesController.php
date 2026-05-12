<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCustomerAddressRequest;
use App\Http\Requests\UpdateCustomerAddressRequest;
use App\Models\User;
use App\Models\UserAddress;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CustomerAddressesController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('customer_address_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $customerAddresses = UserAddress::with(['customer'])
            ->latest()
            ->get();

        return view('admin.customerAddresses.index', compact('customerAddresses'));
    }

    public function create(Request $request)
    {
        abort_if(Gate::denies('customer_address_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $customers = User::whereHas('roles', function ($query) {
                $query->where('title', 'Customer');
            })
            ->where('status', 1)
            ->orderBy('name')
            ->pluck('name', 'id')
            ->prepend('Please Select', '');

        $selectedCustomerId = $request->query('customer_id');

        return view('admin.customerAddresses.create', compact('customers', 'selectedCustomerId'));
    }

    public function store(StoreCustomerAddressRequest $request)
    {
        $data = $request->validated();
        $data['user_id'] = $data['customer_id'];
        unset($data['customer_id']);

        $data['is_default'] = $request->has('is_default') ? 1 : 0;
        $data['status'] = $request->has('status') ? 1 : 0;

        if ($data['is_default']) {
            UserAddress::where('user_id', $data['user_id'])
                ->update(['is_default' => 0]);
        }

        UserAddress::create($data);

        return redirect()
            ->route('admin.customer-addresses.index')
            ->with('message', 'Customer address created successfully.');
    }

    public function show(UserAddress $customerAddress)
    {
        abort_if(Gate::denies('customer_address_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $customerAddress->load(['customer']);

        return view('admin.customerAddresses.show', compact('customerAddress'));
    }

    public function edit(UserAddress $customerAddress)
    {
        abort_if(Gate::denies('customer_address_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $customers = User::whereHas('roles', function ($query) {
                $query->where('title', 'Customer');
            })
            ->where('status', 1)
            ->orderBy('name')
            ->pluck('name', 'id')
            ->prepend('Please Select', '');

        return view('admin.customerAddresses.edit', compact('customerAddress', 'customers'));
    }

    public function update(UpdateCustomerAddressRequest $request, UserAddress $customerAddress)
    {
        $data = $request->validated();
        $data['user_id'] = $data['customer_id'];
        unset($data['customer_id']);

        $data['is_default'] = $request->has('is_default') ? 1 : 0;
        $data['status'] = $request->has('status') ? 1 : 0;

        if ($data['is_default']) {
            UserAddress::where('user_id', $data['user_id'])
                ->where('id', '!=', $customerAddress->id)
                ->update(['is_default' => 0]);
        }

        $customerAddress->update($data);

        return redirect()
            ->route('admin.customer-addresses.index')
            ->with('message', 'Customer address updated successfully.');
    }

    public function destroy(UserAddress $customerAddress)
    {
        abort_if(Gate::denies('customer_address_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $customerAddress->delete();

        return back()->with('message', 'Customer address deleted successfully.');
    }

    public function massDestroy(Request $request)
    {
        abort_if(Gate::denies('customer_address_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        UserAddress::whereIn('id', request('ids', []))->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
