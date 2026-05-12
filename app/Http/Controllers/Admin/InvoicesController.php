<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreInvoiceRequest;
use App\Http\Requests\UpdateInvoiceRequest;
use App\Models\Invoice;
use App\Models\Order;
use App\Models\Payment;
use App\Models\Shop;
use App\Models\User;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class InvoicesController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('invoice_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $invoices = Invoice::with(['order', 'customer', 'shop', 'payment'])->latest()->get();
        return view('admin.invoices.index', compact('invoices'));
    }

    public function create()
    {
        abort_if(Gate::denies('invoice_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        [$orders, $customers, $shops, $payments] = $this->formData();
        return view('admin.invoices.create', compact('orders', 'customers', 'shops', 'payments'));
    }

    public function store(StoreInvoiceRequest $request)
    {
        $data = $request->except('items');
        $data['due_amount'] = max(0, ($data['total_amount'] ?? 0) - ($data['paid_amount'] ?? 0));
        $invoice = Invoice::create($data);
        $this->syncItems($invoice, $request->input('items', []));

        return redirect()->route('admin.invoices.index')->with('message', 'Invoice created successfully.');
    }

    public function generateFromOrder(Order $order)
    {
        abort_if(Gate::denies('invoice_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($order->latestInvoice) {
            return redirect()->route('admin.invoices.show', $order->latestInvoice)->with('message', 'Invoice already exists for this order.');
        }

        $order->load(['customer', 'shop', 'items', 'payments']);
        $paidAmount = $order->payments->where('status', 'paid')->sum('amount');
        $invoice = Invoice::create([
            'order_id' => $order->id,
            'customer_id' => $order->customer_id,
            'shop_id' => $order->shop_id,
            'payment_id' => optional($order->latestPayment)->id,
            'invoice_date' => now()->toDateString(),
            'customer_name' => $order->customer_name ?: optional($order->customer)->name,
            'customer_mobile' => $order->customer_mobile ?: optional($order->customer)->mobile,
            'customer_email' => optional($order->customer)->display_email ?: optional($order->customer)->email,
            'billing_address' => $order->delivery_address,
            'shipping_address' => $order->delivery_address,
            'shop_name' => optional($order->shop)->shop_name,
            'shop_mobile' => optional($order->shop)->mobile,
            'shop_email' => optional($order->shop)->email,
            'shop_address' => optional($order->shop)->address,
            'subtotal' => $order->subtotal,
            'discount_amount' => $order->discount_amount,
            'tax_amount' => $order->tax_amount,
            'delivery_charge' => $order->delivery_charge,
            'total_amount' => $order->total_amount,
            'paid_amount' => $paidAmount,
            'due_amount' => max(0, $order->total_amount - $paidAmount),
            'payment_method' => $order->payment_method,
            'payment_status' => $order->payment_status,
            'invoice_status' => $order->payment_status === 'paid' ? 'paid' : 'issued',
        ]);

        foreach ($order->items as $item) {
            $invoice->items()->create([
                'order_item_id' => $item->id,
                'product_id' => $item->product_id,
                'product_variant_id' => $item->product_variant_id,
                'product_name' => $item->product_name,
                'product_sku' => $item->product_sku,
                'size' => $item->size,
                'color' => $item->color,
                'price' => $item->price,
                'quantity' => $item->quantity,
                'total' => $item->total,
            ]);
        }

        return redirect()->route('admin.invoices.show', $invoice)->with('message', 'Invoice generated successfully.');
    }

    public function show(Invoice $invoice)
    {
        abort_if(Gate::denies('invoice_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $invoice->load(['order', 'customer', 'shop', 'payment', 'items', 'receipts']);
        return view('admin.invoices.show', compact('invoice'));
    }

    public function edit(Invoice $invoice)
    {
        abort_if(Gate::denies('invoice_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $invoice->load('items');
        [$orders, $customers, $shops, $payments] = $this->formData();
        return view('admin.invoices.edit', compact('invoice', 'orders', 'customers', 'shops', 'payments'));
    }

    public function update(UpdateInvoiceRequest $request, Invoice $invoice)
    {
        $data = $request->except('items');
        $data['due_amount'] = max(0, ($data['total_amount'] ?? 0) - ($data['paid_amount'] ?? 0));
        $invoice->update($data);
        return redirect()->route('admin.invoices.index')->with('message', 'Invoice updated successfully.');
    }

    public function print(Invoice $invoice)
    {
        abort_if(Gate::denies('invoice_print'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $invoice->load(['order', 'customer', 'shop', 'payment', 'items', 'receipts']);
        return view('admin.invoices.print', compact('invoice'));
    }

    public function destroy(Invoice $invoice)
    {
        abort_if(Gate::denies('invoice_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $invoice->delete();
        return back()->with('message', 'Invoice deleted successfully.');
    }

    public function massDestroy(Request $request)
    {
        abort_if(Gate::denies('invoice_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        Invoice::whereIn('id', request('ids', []))->delete();
        return response(null, Response::HTTP_NO_CONTENT);
    }

    private function formData(): array
    {
        $orders = Order::latest()->get()->mapWithKeys(fn($order) => [$order->id => $order->order_number . ' - ' . ($order->customer_name ?: 'Customer')])->prepend('Please Select', '');
        $customers = User::whereHas('roles', fn($query) => $query->where('title', 'Customer'))->orderBy('name')->pluck('name', 'id')->prepend('Please Select', '');
        $shops = Shop::orderBy('shop_name')->pluck('shop_name', 'id')->prepend('Please Select', '');
        $payments = Payment::latest()->get()->mapWithKeys(fn($payment) => [$payment->id => '#' . $payment->id . ' - Rs. ' . number_format($payment->amount, 2)])->prepend('Please Select', '');
        return [$orders, $customers, $shops, $payments];
    }

    private function syncItems(Invoice $invoice, array $items): void
    {
        foreach ($items as $item) {
            if (! empty($item['product_name']) || ! empty($item['total'])) {
                $invoice->items()->create($item);
            }
        }
    }
}
