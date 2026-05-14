<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\ReturnRequest;
use Illuminate\Http\Request;

class CustomerOrdersController extends Controller
{
    public function index(Request $request)
    {
        $orders = collect();

        if (auth()->check()) {
            $orders = Order::with(['items', 'latestPayment'])
                ->where('customer_id', auth()->id())
                ->latest()
                ->limit(20)
                ->get();
        } elseif ($request->filled('mobile') || $request->filled('order_number')) {
            $orders = Order::with(['items', 'latestPayment'])
                ->when($request->filled('mobile'), fn ($query) => $query->where('customer_mobile', $request->mobile))
                ->when($request->filled('order_number'), fn ($query) => $query->where('order_number', $request->order_number))
                ->latest()
                ->limit(20)
                ->get();

            session(['verified_order_ids' => $orders->pluck('id')->all()]);
        }

        return view('frontend.orders.index', compact('orders'));
    }

    public function show(Order $order)
    {
        $this->authorizeOrderAccess($order);

        $order->load([
            'items.product.media',
            'payments',
            'statusHistories',
            'deliveryTracking',
            'returnRequests.orderItem',
            'latestInvoice',
            'latestPayment.receipts',
        ]);

        return view('frontend.orders.show', compact('order'));
    }

    public function storeReturn(Request $request, Order $order, OrderItem $orderItem)
    {
        $this->authorizeOrderAccess($order);
        abort_if((int) $orderItem->order_id !== (int) $order->id, 404);

        $request->validate([
            'reason' => ['required', 'string', 'max:120'],
            'description' => ['nullable', 'string', 'max:500'],
        ]);

        if (! $this->isReturnEligible($orderItem)) {
            return back()->withErrors([
                'return' => 'Try Cloth selected tha, isliye is product ka return available nahi hai.',
            ]);
        }

        $alreadyRequested = ReturnRequest::where('order_item_id', $orderItem->id)
            ->whereNotIn('status', ['cancelled', 'rejected'])
            ->exists();

        if ($alreadyRequested) {
            return back()->withErrors([
                'return' => 'Is product ke liye return request pehle se submit hai.',
            ]);
        }

        ReturnRequest::create([
            'order_id' => $order->id,
            'order_item_id' => $orderItem->id,
            'customer_id' => $order->customer_id,
            'shop_id' => $orderItem->shop_id ?: $order->shop_id,
            'product_name' => $orderItem->product_name,
            'size' => $orderItem->size,
            'color' => $orderItem->color,
            'quantity' => $orderItem->quantity ?: 1,
            'price' => $orderItem->price,
            'refund_amount' => $orderItem->total ?: ((float) $orderItem->price * ($orderItem->quantity ?: 1)),
            'reason' => $request->reason,
            'description' => $request->description,
            'status' => 'requested',
            'requested_at' => now(),
        ]);

        return back()->with('message', 'Return request submit ho gayi. Admin panel me review ke liye chali gayi hai.');
    }

    private function isReturnEligible(OrderItem $orderItem): bool
    {
        return (bool) $orderItem->return_eligible && ! (bool) $orderItem->try_cloth_selected;
    }

    private function authorizeOrderAccess(Order $order): void
    {
        if (auth()->check()) {
            abort_if((int) $order->customer_id !== (int) auth()->id(), 403);
            return;
        }

        $allowedIds = session('verified_order_ids', []);
        $lastOrderId = session('last_order_id');

        abort_if(! in_array($order->id, $allowedIds, true) && (int) $lastOrderId !== (int) $order->id, 403);
    }
}
