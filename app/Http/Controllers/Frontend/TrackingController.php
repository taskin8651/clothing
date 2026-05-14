<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\DeliveryTracking;
use App\Models\Order;
use Illuminate\Http\Request;

class TrackingController extends Controller
{
    public function index(Request $request)
    {
        $orders = collect();

        if ($request->filled('order_number') || $request->filled('tracking_number') || $request->filled('mobile')) {
            $orders = Order::with(['deliveryTracking.deliveryBoy', 'statusHistories', 'items'])
                ->when($request->filled('order_number'), fn ($query) => $query->where('order_number', $request->order_number))
                ->when($request->filled('mobile'), fn ($query) => $query->where('customer_mobile', $request->mobile))
                ->when($request->filled('tracking_number'), function ($query) use ($request) {
                    $query->whereHas('deliveryTracking', fn ($trackingQuery) => $trackingQuery->where('tracking_number', $request->tracking_number));
                })
                ->latest()
                ->limit(20)
                ->get();
        }

        return view('frontend.tracking.index', compact('orders'));
    }

    public function show(DeliveryTracking $deliveryTracking)
    {
        $deliveryTracking->load(['order.items.product.media', 'deliveryBoy', 'shop']);

        return view('frontend.tracking.show', compact('deliveryTracking'));
    }
}
