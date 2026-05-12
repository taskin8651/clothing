<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdatePaymentRequest;
use App\Http\Requests\UpdatePaymentStatusRequest;
use App\Models\Order;
use App\Models\Payment;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class PaymentsController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('payment_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $payments = Payment::with(['order.customer', 'order.shop'])->latest()->get();

        return view('admin.payments.index', compact('payments'));
    }

    public function show(Payment $payment)
    {
        abort_if(Gate::denies('payment_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $payment->load(['order.customer', 'order.shop', 'order.deliveryBoy']);

        return view('admin.payments.show', compact('payment'));
    }

    public function edit(Payment $payment)
    {
        abort_if(Gate::denies('payment_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $payment->load(['order']);
        $orders = Order::latest()
            ->get()
            ->mapWithKeys(function ($order) {
                return [$order->id => $order->order_number . ' - ' . ($order->customer_name ?: 'Customer')];
            })
            ->prepend('Please Select', '');

        return view('admin.payments.edit', compact('payment', 'orders'));
    }

    public function update(UpdatePaymentRequest $request, Payment $payment)
    {
        $data = $request->validated();
        $data['gateway_response'] = $this->decodeGatewayResponse($data['gateway_response'] ?? null);

        if ($data['status'] === 'paid' && empty($data['paid_at'])) {
            $data['paid_at'] = now();
        }

        $payment->update($data);
        $this->syncOrderPayment($payment);

        return redirect()
            ->route('admin.payments.index')
            ->with('message', 'Payment updated successfully.');
    }

    public function updateStatus(UpdatePaymentStatusRequest $request, Payment $payment)
    {
        $data = ['status' => $request->status];

        if ($request->status === 'paid' && ! $payment->paid_at) {
            $data['paid_at'] = now();
        }

        if ($request->status !== 'paid' && $request->has('clear_paid_at')) {
            $data['paid_at'] = null;
        }

        $payment->update($data);
        $this->syncOrderPayment($payment, $request->admin_note);

        return back()->with('message', 'Payment status updated successfully.');
    }

    public function destroy(Payment $payment)
    {
        abort_if(Gate::denies('payment_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $payment->delete();

        return back()->with('message', 'Payment deleted successfully.');
    }

    public function massDestroy(Request $request)
    {
        abort_if(Gate::denies('payment_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        Payment::whereIn('id', request('ids', []))->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }

    private function syncOrderPayment(Payment $payment, ?string $adminNote = null): void
    {
        if (! $payment->order) {
            return;
        }

        $data = [
            'payment_status' => $payment->status,
            'payment_method' => $payment->payment_method,
        ];

        if ($adminNote) {
            $data['admin_note'] = trim(($payment->order->admin_note ? $payment->order->admin_note . "\n" : '') . 'Payment note: ' . $adminNote);
        }

        $payment->order->update($data);
    }

    private function decodeGatewayResponse($value)
    {
        if ($value === null || $value === '') {
            return null;
        }

        if (is_array($value)) {
            return $value;
        }

        $decoded = json_decode($value, true);

        return json_last_error() === JSON_ERROR_NONE ? $decoded : ['raw' => $value];
    }
}
