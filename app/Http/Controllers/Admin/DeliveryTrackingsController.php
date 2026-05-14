<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\AssignDeliveryTrackingBoyRequest;
use App\Http\Requests\StoreDeliveryTrackingRequest;
use App\Http\Requests\UpdateDeliveryTrackingRequest;
use App\Http\Requests\UpdateDeliveryTrackingStatusRequest;
use App\Models\DeliveryBoy;
use App\Models\DeliveryTracking;
use App\Models\Order;
use App\Models\Shop;
use App\Models\User;
use App\Models\UserAddress;
use App\Services\FinanceDocumentService;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class DeliveryTrackingsController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('delivery_tracking_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $deliveryTrackings = DeliveryTracking::with(['order', 'shop', 'deliveryBoy', 'customer', 'customerAddress'])
            ->latest()
            ->get();

        return view('admin.deliveryTrackings.index', compact('deliveryTrackings'));
    }

    public function create()
    {
        abort_if(Gate::denies('delivery_tracking_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        [$orders, $shops, $deliveryBoys, $customers, $customerAddresses] = $this->formData();

        return view('admin.deliveryTrackings.create', compact('orders', 'shops', 'deliveryBoys', 'customers', 'customerAddresses'));
    }

    public function store(StoreDeliveryTrackingRequest $request)
    {
        $data = $request->validated();
        $order = ! empty($data['order_id']) ? Order::with(['shop', 'customerAddress'])->find($data['order_id']) : null;

        if ($order) {
            $data['customer_id'] = $data['customer_id'] ?? $order->customer_id;
            $data['customer_address_id'] = $data['customer_address_id'] ?? $order->customer_address_id;
            $data['shop_id'] = $data['shop_id'] ?? $order->shop_id;
            $data['delivery_boy_id'] = $data['delivery_boy_id'] ?? $order->delivery_boy_id;
            $data['delivery_address'] = $data['delivery_address'] ?? $order->delivery_address;
            $data['city'] = $data['city'] ?? $order->city;
            $data['area'] = $data['area'] ?? $order->area;
            $data['pincode'] = $data['pincode'] ?? $order->pincode;
            $data['latitude'] = $data['latitude'] ?? $order->latitude;
            $data['longitude'] = $data['longitude'] ?? $order->longitude;
            $data['pickup_address'] = $data['pickup_address'] ?? optional($order->shop)->address;

            if ($order->payment_method === 'cod') {
                $data['cod_amount'] = $data['cod_amount'] ?? $order->total_amount;
            }
        }

        if (! empty($data['customer_address_id']) && empty($data['delivery_address'])) {
            $address = UserAddress::find($data['customer_address_id']);
            $data['delivery_address'] = $address ? $address->address : null;
            $data['city'] = $data['city'] ?? optional($address)->city;
            $data['area'] = $data['area'] ?? optional($address)->area;
            $data['pincode'] = $data['pincode'] ?? optional($address)->pincode;
            $data['latitude'] = $data['latitude'] ?? optional($address)->latitude;
            $data['longitude'] = $data['longitude'] ?? optional($address)->longitude;
        }

        if (! empty($data['shop_id']) && empty($data['pickup_address'])) {
            $data['pickup_address'] = optional(Shop::find($data['shop_id']))->address;
        }

        $data['cod_collected'] = $request->has('cod_collected') ? 1 : 0;
        $data['status'] = ! empty($data['delivery_boy_id']) ? 'assigned' : ($data['status'] ?? 'pending');

        $deliveryTracking = DeliveryTracking::create($data);
        $this->syncStatusTimestamp($deliveryTracking, $deliveryTracking->status);

        if ($deliveryTracking->order && $deliveryTracking->delivery_boy_id) {
            $deliveryTracking->order->update([
                'delivery_boy_id' => $deliveryTracking->delivery_boy_id,
                'order_status' => 'assigned',
                'assigned_at' => now(),
            ]);
        }

        return redirect()
            ->route('admin.delivery-trackings.index')
            ->with('message', 'Delivery tracking created successfully.');
    }

    public function show(DeliveryTracking $deliveryTracking)
    {
        abort_if(Gate::denies('delivery_tracking_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $deliveryTracking->load(['order', 'shop', 'deliveryBoy', 'customer', 'customerAddress']);
        [$orders, $shops, $deliveryBoys, $customers, $customerAddresses] = $this->formData();

        return view('admin.deliveryTrackings.show', compact(
            'deliveryTracking',
            'orders',
            'shops',
            'deliveryBoys',
            'customers',
            'customerAddresses'
        ));
    }

    public function edit(DeliveryTracking $deliveryTracking)
    {
        abort_if(Gate::denies('delivery_tracking_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $deliveryTracking->load(['order', 'shop', 'deliveryBoy', 'customer', 'customerAddress']);
        [$orders, $shops, $deliveryBoys, $customers, $customerAddresses] = $this->formData();

        return view('admin.deliveryTrackings.edit', compact(
            'deliveryTracking',
            'orders',
            'shops',
            'deliveryBoys',
            'customers',
            'customerAddresses'
        ));
    }

    public function update(UpdateDeliveryTrackingRequest $request, DeliveryTracking $deliveryTracking)
    {
        $oldStatus = $deliveryTracking->status;
        $oldDeliveryBoyId = $deliveryTracking->delivery_boy_id;
        $data = $request->validated();
        $data['cod_collected'] = $request->has('cod_collected') ? 1 : 0;

        $deliveryTracking->update($data);

        if ($request->filled('status') && $request->status !== $oldStatus) {
            $this->syncStatusTimestamp($deliveryTracking, $request->status);
            $this->syncOrderStatus($deliveryTracking, $request->status);
        }

        if ((int) $oldDeliveryBoyId !== (int) $deliveryTracking->delivery_boy_id && $deliveryTracking->order) {
            $deliveryTracking->order->update(['delivery_boy_id' => $deliveryTracking->delivery_boy_id]);
        }

        return redirect()
            ->route('admin.delivery-trackings.index')
            ->with('message', 'Delivery tracking updated successfully.');
    }

    public function updateStatus(UpdateDeliveryTrackingStatusRequest $request, DeliveryTracking $deliveryTracking)
    {
        $deliveryTracking->update($request->only([
            'status',
            'failure_reason',
            'delivery_note',
            'admin_note',
        ]));

        $this->syncStatusTimestamp($deliveryTracking, $request->status);
        $this->syncOrderStatus($deliveryTracking, $request->status);

        return back()->with('message', 'Delivery status updated successfully.');
    }

    public function assignDeliveryBoy(AssignDeliveryTrackingBoyRequest $request, DeliveryTracking $deliveryTracking)
    {
        $deliveryTracking->update([
            'delivery_boy_id' => $request->delivery_boy_id,
            'status' => 'assigned',
            'assigned_at' => now(),
            'admin_note' => $request->admin_note ?: $deliveryTracking->admin_note,
        ]);

        if ($deliveryTracking->order) {
            $deliveryTracking->order->update([
                'delivery_boy_id' => $request->delivery_boy_id,
                'order_status' => 'assigned',
                'assigned_at' => now(),
            ]);
        }

        return back()->with('message', 'Delivery boy assigned successfully.');
    }

    public function markCodCollected(DeliveryTracking $deliveryTracking, FinanceDocumentService $documents)
    {
        abort_if(Gate::denies('delivery_tracking_cod_update'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $deliveryTracking->update([
            'cod_collected' => 1,
            'cod_collected_at' => now(),
        ]);

        if ($deliveryTracking->order && $deliveryTracking->order->payment_method === 'cod') {
            $deliveryTracking->order->update(['payment_status' => 'paid']);
            $payment = $deliveryTracking->order->payments()
                ->where('payment_method', 'cod')
                ->latest()
                ->first();

            if ($payment) {
                $payment->update([
                    'status' => 'paid',
                    'paid_at' => now(),
                ]);
                $documents->generateReceiptFromPayment($payment->fresh('order'));
            }
        }

        return back()->with('message', 'COD marked as collected successfully.');
    }

    public function destroy(DeliveryTracking $deliveryTracking)
    {
        abort_if(Gate::denies('delivery_tracking_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $deliveryTracking->delete();

        return back()->with('message', 'Delivery tracking deleted successfully.');
    }

    public function massDestroy(Request $request)
    {
        abort_if(Gate::denies('delivery_tracking_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        DeliveryTracking::whereIn('id', request('ids', []))->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }

    private function formData(): array
    {
        $orders = Order::with(['customer', 'shop'])
            ->latest()
            ->get()
            ->mapWithKeys(function ($order) {
                return [$order->id => $order->order_number . ' - ' . ($order->customer_name ?: optional($order->customer)->name ?: 'Customer')];
            })
            ->prepend('Please Select', '');

        $shops = Shop::where('status', 1)->orderBy('shop_name')->pluck('shop_name', 'id')->prepend('Please Select', '');
        $deliveryBoys = DeliveryBoy::where('status', 1)->orderBy('name')->pluck('name', 'id')->prepend('Please Select', '');
        $customers = User::whereHas('roles', function ($query) {
                $query->where('title', 'Customer');
            })
            ->orderBy('name')
            ->pluck('name', 'id')
            ->prepend('Please Select', '');
        $customerAddresses = UserAddress::with('user')->latest()->get();

        return [$orders, $shops, $deliveryBoys, $customers, $customerAddresses];
    }

    private function syncStatusTimestamp(DeliveryTracking $deliveryTracking, string $status): void
    {
        $columns = [
            'assigned' => 'assigned_at',
            'pickup_pending' => 'pickup_pending_at',
            'picked_up' => 'picked_up_at',
            'out_for_delivery' => 'out_for_delivery_at',
            'delivered' => 'delivered_at',
            'failed_delivery' => 'failed_delivery_at',
            'cancelled' => 'cancelled_at',
        ];

        if (isset($columns[$status]) && ! $deliveryTracking->{$columns[$status]}) {
            $deliveryTracking->update([$columns[$status] => now()]);
        }
    }

    private function syncOrderStatus(DeliveryTracking $deliveryTracking, string $status): void
    {
        if (! $deliveryTracking->order) {
            return;
        }

        $map = [
            'assigned' => 'assigned',
            'picked_up' => 'picked_up',
            'out_for_delivery' => 'out_for_delivery',
            'delivered' => 'delivered',
            'cancelled' => 'cancelled',
        ];

        if ($status === 'failed_delivery') {
            $deliveryTracking->order->update([
                'order_status' => 'out_for_delivery',
                'admin_note' => trim(($deliveryTracking->order->admin_note ? $deliveryTracking->order->admin_note . "\n" : '') . 'Failed delivery: ' . ($deliveryTracking->failure_reason ?: 'No reason added')),
            ]);
            return;
        }

        if (isset($map[$status])) {
            $data = ['order_status' => $map[$status]];

            if ($status === 'delivered') {
                $data['delivered_at'] = $deliveryTracking->order->delivered_at ?: now();
            }

            $deliveryTracking->order->update($data);
        }
    }
}
