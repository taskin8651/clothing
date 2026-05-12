<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreReturnRequestRequest;
use App\Http\Requests\UpdateReturnRequestRequest;
use App\Http\Requests\UpdateReturnStatusRequest;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\ReturnRequest;
use App\Models\Shop;
use App\Models\User;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ReturnRequestsController extends Controller
{
    private const INELIGIBLE_MESSAGE = 'This item is not eligible for return because Try Cloth was selected.';

    public function index()
    {
        abort_if(Gate::denies('return_request_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $returnRequests = ReturnRequest::with(['order', 'orderItem', 'customer', 'shop'])->latest()->get();
        return view('admin.returnRequests.index', compact('returnRequests'));
    }

    public function create()
    {
        abort_if(Gate::denies('return_request_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        [$orders, $orderItems, $customers, $shops] = $this->formData();
        return view('admin.returnRequests.create', compact('orders', 'orderItems', 'customers', 'shops'));
    }

    public function store(StoreReturnRequestRequest $request)
    {
        $orderItem = OrderItem::with('order')->findOrFail($request->order_item_id);
        if (! $this->isEligible($orderItem)) {
            return back()->withInput()->withErrors(['order_item_id' => self::INELIGIBLE_MESSAGE]);
        }

        $order = $orderItem->order ?: Order::find($request->order_id);
        $quantity = $orderItem->quantity ?: 1;
        $refundAmount = $request->refund_amount ?? ($orderItem->total ?: ((float) $orderItem->price * $quantity));

        ReturnRequest::create([
            'order_id' => $request->order_id,
            'order_item_id' => $orderItem->id,
            'customer_id' => $request->customer_id ?: optional($order)->customer_id,
            'shop_id' => $request->shop_id ?: ($orderItem->shop_id ?: optional($order)->shop_id),
            'product_name' => $orderItem->product_name,
            'size' => $orderItem->size,
            'color' => $orderItem->color,
            'quantity' => $quantity,
            'price' => $orderItem->price,
            'refund_amount' => $refundAmount,
            'reason' => $request->reason,
            'description' => $request->description,
            'status' => 'requested',
            'admin_note' => $request->admin_note,
            'requested_at' => now(),
        ]);

        return redirect()->route('admin.return-requests.index')->with('message', 'Return request created successfully.');
    }

    public function show(ReturnRequest $returnRequest)
    {
        abort_if(Gate::denies('return_request_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $returnRequest->load(['order', 'orderItem', 'customer', 'shop']);
        return view('admin.returnRequests.show', compact('returnRequest'));
    }

    public function edit(ReturnRequest $returnRequest)
    {
        abort_if(Gate::denies('return_request_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $returnRequest->load(['order', 'orderItem', 'customer', 'shop']);
        [$orders, $orderItems, $customers, $shops] = $this->formData();
        return view('admin.returnRequests.edit', compact('returnRequest', 'orders', 'orderItems', 'customers', 'shops'));
    }

    public function update(UpdateReturnRequestRequest $request, ReturnRequest $returnRequest)
    {
        $oldStatus = $returnRequest->status;
        $orderItem = OrderItem::findOrFail($request->order_item_id);

        if ((int) $returnRequest->order_item_id !== (int) $orderItem->id && ! $this->isEligible($orderItem)) {
            return back()->withInput()->withErrors(['order_item_id' => self::INELIGIBLE_MESSAGE]);
        }

        $data = $request->validated();
        $data['product_name'] = $orderItem->product_name;
        $data['size'] = $orderItem->size;
        $data['color'] = $orderItem->color;
        $data['quantity'] = $orderItem->quantity ?: 1;
        $data['price'] = $orderItem->price;

        $returnRequest->update($data);

        if ($request->filled('status') && $request->status !== $oldStatus) {
            $this->syncStatusTimestamp($returnRequest, $request->status);
        }

        return redirect()->route('admin.return-requests.index')->with('message', 'Return request updated successfully.');
    }

    public function updateStatus(UpdateReturnStatusRequest $request, ReturnRequest $returnRequest)
    {
        $returnRequest->update([
            'status' => $request->status,
            'admin_note' => $request->admin_note ?: $returnRequest->admin_note,
        ]);
        $this->syncStatusTimestamp($returnRequest, $request->status);

        return back()->with('message', 'Return status updated successfully.');
    }

    public function destroy(ReturnRequest $returnRequest)
    {
        abort_if(Gate::denies('return_request_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $returnRequest->delete();
        return back()->with('message', 'Return request deleted successfully.');
    }

    public function massDestroy(Request $request)
    {
        abort_if(Gate::denies('return_request_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        ReturnRequest::whereIn('id', request('ids', []))->delete();
        return response(null, Response::HTTP_NO_CONTENT);
    }

    private function isEligible(OrderItem $orderItem): bool
    {
        return (bool) $orderItem->return_eligible && ! (bool) $orderItem->try_cloth_selected;
    }

    private function syncStatusTimestamp(ReturnRequest $returnRequest, string $status): void
    {
        $columns = [
            'approved' => 'approved_at',
            'rejected' => 'rejected_at',
            'picked_up' => 'picked_up_at',
            'refunded' => 'refunded_at',
            'cancelled' => 'cancelled_at',
        ];

        if (isset($columns[$status]) && ! $returnRequest->{$columns[$status]}) {
            $returnRequest->update([$columns[$status] => now()]);
        }
    }

    private function formData(): array
    {
        $orders = Order::latest()->get()->mapWithKeys(fn($order) => [$order->id => $order->order_number . ' - ' . ($order->customer_name ?: 'Customer')])->prepend('Please Select', '');
        $orderItems = OrderItem::with('order')->latest()->get();
        $customers = User::whereHas('roles', fn($query) => $query->where('title', 'Customer'))->orderBy('name')->pluck('name', 'id')->prepend('Please Select', '');
        $shops = Shop::orderBy('shop_name')->pluck('shop_name', 'id')->prepend('Please Select', '');
        return [$orders, $orderItems, $customers, $shops];
    }
}
