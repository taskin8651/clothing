<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreReceiptRequest;
use App\Http\Requests\UpdateReceiptRequest;
use App\Models\Invoice;
use App\Models\Order;
use App\Models\Payment;
use App\Models\Receipt;
use App\Models\Shop;
use App\Models\User;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ReceiptsController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('receipt_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $receipts = Receipt::with(['invoice', 'order', 'payment', 'customer', 'shop'])->latest()->get();
        return view('admin.receipts.index', compact('receipts'));
    }

    public function create()
    {
        abort_if(Gate::denies('receipt_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        [$invoices, $orders, $payments, $customers, $shops] = $this->formData();
        return view('admin.receipts.create', compact('invoices', 'orders', 'payments', 'customers', 'shops'));
    }

    public function store(StoreReceiptRequest $request)
    {
        $receipt = Receipt::create($request->validated());
        $this->syncInvoice($receipt);
        $this->syncPayment($receipt);
        return redirect()->route('admin.receipts.index')->with('message', 'Receipt created successfully.');
    }

    public function generateFromPayment(Payment $payment)
    {
        abort_if(Gate::denies('receipt_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($payment->receipts()->exists()) {
            return redirect()->route('admin.receipts.show', $payment->receipts()->latest()->first())->with('message', 'Receipt already exists for this payment.');
        }

        $payment->load('order.latestInvoice');
        $order = $payment->order;
        $receipt = Receipt::create([
            'invoice_id' => optional($order)->latestInvoice ? $order->latestInvoice->id : null,
            'order_id' => optional($order)->id,
            'payment_id' => $payment->id,
            'customer_id' => optional($order)->customer_id,
            'shop_id' => optional($order)->shop_id,
            'receipt_type' => $payment->payment_method === 'cod' ? 'cod' : 'payment',
            'receipt_date' => now()->toDateString(),
            'payment_method' => $payment->payment_method,
            'payment_gateway' => $payment->payment_gateway,
            'transaction_id' => $payment->transaction_id,
            'amount' => $payment->amount,
            'status' => $payment->status ?: 'paid',
            'received_from' => optional($order)->customer_name,
        ]);
        $this->syncInvoice($receipt);
        return redirect()->route('admin.receipts.show', $receipt)->with('message', 'Receipt generated successfully.');
    }

    public function show(Receipt $receipt)
    {
        abort_if(Gate::denies('receipt_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $receipt->load(['invoice', 'order', 'payment', 'customer', 'shop']);
        return view('admin.receipts.show', compact('receipt'));
    }

    public function edit(Receipt $receipt)
    {
        abort_if(Gate::denies('receipt_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        [$invoices, $orders, $payments, $customers, $shops] = $this->formData();
        return view('admin.receipts.edit', compact('receipt', 'invoices', 'orders', 'payments', 'customers', 'shops'));
    }

    public function update(UpdateReceiptRequest $request, Receipt $receipt)
    {
        $receipt->update($request->validated());
        $this->syncInvoice($receipt);
        $this->syncPayment($receipt);
        return redirect()->route('admin.receipts.index')->with('message', 'Receipt updated successfully.');
    }

    public function print(Receipt $receipt)
    {
        abort_if(Gate::denies('receipt_print'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $receipt->load(['invoice', 'order', 'payment', 'customer', 'shop']);
        return view('admin.receipts.print', compact('receipt'));
    }

    public function destroy(Receipt $receipt)
    {
        abort_if(Gate::denies('receipt_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $receipt->delete();
        $this->syncInvoice($receipt);
        return back()->with('message', 'Receipt deleted successfully.');
    }

    public function massDestroy(Request $request)
    {
        abort_if(Gate::denies('receipt_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        Receipt::whereIn('id', request('ids', []))->delete();
        return response(null, Response::HTTP_NO_CONTENT);
    }

    private function syncInvoice(Receipt $receipt): void
    {
        if (! $receipt->invoice) {
            return;
        }
        $paid = $receipt->invoice->receipts()->where('status', 'paid')->sum('amount');
        $due = max(0, $receipt->invoice->total_amount - $paid);
        $receipt->invoice->update([
            'paid_amount' => $paid,
            'due_amount' => $due,
            'invoice_status' => $due <= 0 ? 'paid' : $receipt->invoice->invoice_status,
        ]);
    }

    private function syncPayment(Receipt $receipt): void
    {
        if ($receipt->payment && $receipt->status === 'paid') {
            $receipt->payment->update(['status' => 'paid', 'paid_at' => $receipt->payment->paid_at ?: now()]);
        }
    }

    private function formData(): array
    {
        $invoices = Invoice::latest()->get()->mapWithKeys(fn($invoice) => [$invoice->id => $invoice->invoice_number])->prepend('Please Select', '');
        $orders = Order::latest()->get()->mapWithKeys(fn($order) => [$order->id => $order->order_number])->prepend('Please Select', '');
        $payments = Payment::latest()->get()->mapWithKeys(fn($payment) => [$payment->id => '#' . $payment->id . ' - Rs. ' . number_format($payment->amount, 2)])->prepend('Please Select', '');
        $customers = User::whereHas('roles', fn($query) => $query->where('title', 'Customer'))->orderBy('name')->pluck('name', 'id')->prepend('Please Select', '');
        $shops = Shop::orderBy('shop_name')->pluck('shop_name', 'id')->prepend('Please Select', '');
        return [$invoices, $orders, $payments, $customers, $shops];
    }
}
