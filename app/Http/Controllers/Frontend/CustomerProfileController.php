<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\ReturnRequest;
use App\Models\UserAddress;
use Illuminate\Http\Request;

class CustomerProfileController extends Controller
{
    public function profile()
    {
        $customer = auth()->user()->load(['addresses' => fn ($query) => $query->latest()]);

        $orders = Order::with(['items', 'deliveryTracking', 'returnRequests'])
            ->where('customer_id', $customer->id)
            ->latest()
            ->limit(10)
            ->get();

        $returnRequests = ReturnRequest::with(['order', 'orderItem'])
            ->where('customer_id', $customer->id)
            ->latest()
            ->limit(10)
            ->get();

        $wishlistProducts = $customer->wishlists()
            ->with(['product.category', 'product.media'])
            ->latest()
            ->limit(8)
            ->get()
            ->pluck('product')
            ->filter();

        $recentProducts = \App\Models\Product::with(['category', 'media'])
            ->whereIn('id', session('recently_viewed_products', []))
            ->get()
            ->sortBy(fn ($product) => array_search($product->id, session('recently_viewed_products', []), true))
            ->values();

        return view('frontend.customer.profile', compact('customer', 'orders', 'returnRequests', 'wishlistProducts', 'recentProducts'));
    }

    public function storeAddress(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:80'],
            'mobile' => ['required', 'string', 'max:20'],
            'address' => ['required', 'string', 'max:500'],
            'landmark' => ['nullable', 'string', 'max:160'],
            'city' => ['required', 'string', 'max:80'],
            'area' => ['nullable', 'string', 'max:120'],
            'pincode' => ['required', 'string', 'max:12'],
            'is_default' => ['nullable', 'boolean'],
        ]);

        $data['user_id'] = auth()->id();
        $data['status'] = 1;
        $data['is_default'] = $request->boolean('is_default') || ! auth()->user()->addresses()->exists();

        if ($data['is_default']) {
            auth()->user()->addresses()->update(['is_default' => 0]);
        }

        UserAddress::create($data);

        return back()->with('message', 'Address save ho gaya.');
    }

    public function setDefaultAddress(UserAddress $address)
    {
        abort_if((int) $address->user_id !== (int) auth()->id(), 403);

        auth()->user()->addresses()->update(['is_default' => 0]);
        $address->update(['is_default' => 1]);

        return back()->with('message', 'Default address update ho gaya.');
    }

    public function deleteAddress(UserAddress $address)
    {
        abort_if((int) $address->user_id !== (int) auth()->id(), 403);

        $address->delete();

        return back()->with('message', 'Address delete ho gaya.');
    }
}
