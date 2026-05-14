<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\DeliveryBoy;
use App\Models\DeliveryTracking;
use App\Services\FinanceDocumentService;
use Illuminate\Http\Request;

class DeliveryBoyPanelController extends Controller
{
    public function index(Request $request)
    {
        $deliveryBoy = null;
        $trackings = collect();

        if ($request->filled('mobile')) {
            $deliveryBoy = DeliveryBoy::where('status', 1)
                ->where('mobile', $request->mobile)
                ->first();

            if ($deliveryBoy) {
                $trackings = DeliveryTracking::with(['order.items', 'shop'])
                    ->where('delivery_boy_id', $deliveryBoy->id)
                    ->whereNotIn('status', ['delivered', 'cancelled'])
                    ->latest()
                    ->get();
            }
        }

        return view('frontend.delivery-boy.index', compact('deliveryBoy', 'trackings'));
    }

    public function updateStatus(Request $request, DeliveryTracking $deliveryTracking)
    {
        $data = $request->validate([
            'delivery_boy_id' => ['required', 'exists:delivery_boys,id'],
            'status' => ['required', 'in:pickup_pending,picked_up,out_for_delivery,delivered,failed_delivery,cancelled'],
            'failure_reason' => ['nullable', 'string', 'max:160'],
            'delivery_note' => ['nullable', 'string', 'max:300'],
        ]);

        abort_if((int) $deliveryTracking->delivery_boy_id !== (int) $data['delivery_boy_id'], 403);

        $deliveryTracking->update([
            'status' => $data['status'],
            'failure_reason' => $data['failure_reason'] ?? null,
            'delivery_note' => $data['delivery_note'] ?? $deliveryTracking->delivery_note,
        ]);

        $this->syncTrackingTimestamp($deliveryTracking, $data['status']);
        $this->syncOrderStatus($deliveryTracking, $data['status']);

        return back()->with('message', 'Delivery status update ho gaya.');
    }

    public function markCodCollected(Request $request, DeliveryTracking $deliveryTracking, FinanceDocumentService $documents)
    {
        $data = $request->validate([
            'delivery_boy_id' => ['required', 'exists:delivery_boys,id'],
        ]);

        abort_if((int) $deliveryTracking->delivery_boy_id !== (int) $data['delivery_boy_id'], 403);

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

        return back()->with('message', 'COD collected mark ho gaya.');
    }

    private function syncTrackingTimestamp(DeliveryTracking $tracking, string $status): void
    {
        $columns = [
            'pickup_pending' => 'pickup_pending_at',
            'picked_up' => 'picked_up_at',
            'out_for_delivery' => 'out_for_delivery_at',
            'delivered' => 'delivered_at',
            'failed_delivery' => 'failed_delivery_at',
            'cancelled' => 'cancelled_at',
        ];

        if (isset($columns[$status]) && ! $tracking->{$columns[$status]}) {
            $tracking->update([$columns[$status] => now()]);
        }
    }

    private function syncOrderStatus(DeliveryTracking $tracking, string $status): void
    {
        if (! $tracking->order) {
            return;
        }

        $map = [
            'picked_up' => 'picked_up',
            'out_for_delivery' => 'out_for_delivery',
            'delivered' => 'delivered',
            'cancelled' => 'cancelled',
        ];

        if ($status === 'failed_delivery') {
            $tracking->order->update([
                'order_status' => 'out_for_delivery',
                'admin_note' => trim(($tracking->order->admin_note ? $tracking->order->admin_note . "\n" : '') . 'Failed delivery: ' . ($tracking->failure_reason ?: 'No reason added')),
            ]);
            return;
        }

        if (! isset($map[$status])) {
            return;
        }

        $data = ['order_status' => $map[$status]];

        if ($status === 'delivered') {
            $data['delivered_at'] = $tracking->order->delivered_at ?: now();
        }

        $tracking->order->update($data);
        $tracking->order->statusHistories()->create([
            'status' => $map[$status],
            'note' => 'Updated from delivery boy panel',
            'changed_by_id' => $tracking->delivery_boy_id,
            'changed_by_type' => DeliveryBoy::class,
        ]);
    }
}
