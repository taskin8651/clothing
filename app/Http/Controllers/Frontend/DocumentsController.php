<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use App\Models\Order;
use App\Models\Payment;
use App\Models\Receipt;
use App\Services\FinanceDocumentService;
use App\Services\CustomerNotificationService;
use Illuminate\Http\Request;

class DocumentsController extends Controller
{
    public function invoice(Invoice $invoice)
    {
        $this->authorizeOrderAccess($invoice->order);
        $invoice->load(['order', 'customer', 'shop', 'payment', 'items', 'receipts']);

        return view('frontend.documents.invoice', compact('invoice'));
    }

    public function receipt(Receipt $receipt)
    {
        $this->authorizeOrderAccess($receipt->order);
        $receipt->load(['invoice', 'order', 'payment', 'customer', 'shop']);

        return view('frontend.documents.receipt', compact('receipt'));
    }

    public function payment(Order $order, FinanceDocumentService $documents)
    {
        $this->authorizeOrderAccess($order);
        $invoice = $documents->generateInvoiceFromOrder($order);
        $order->load(['latestPayment', 'items']);

        return view('frontend.documents.payment', compact('order', 'invoice'));
    }

    public function markPaymentSuccess(Request $request, Order $order, FinanceDocumentService $documents, CustomerNotificationService $notifications)
    {
        $this->authorizeOrderAccess($order);
        $order->load('latestPayment');

        $payment = $order->latestPayment ?: $order->payments()->create([
            'payment_method' => $order->payment_method ?: 'online',
            'amount' => $order->total_amount,
            'status' => 'pending',
        ]);

        $payment->update([
            'payment_method' => 'online',
            'payment_gateway' => 'demo',
            'transaction_id' => 'DEMO-' . now()->format('YmdHis') . '-' . $order->id,
            'status' => 'paid',
            'paid_at' => now(),
        ]);

        $order->update([
            'payment_method' => 'online',
            'payment_status' => 'paid',
        ]);

        $invoice = $documents->generateInvoiceFromOrder($order->fresh(['payments', 'latestPayment']));
        $receipt = $documents->generateReceiptFromPayment($payment->fresh('order'));
        $order->load(['customer', 'latestPayment.receipts']);
        $notifications->paymentPaid($order);

        return redirect()
            ->route('frontend.receipts.show', $receipt)
            ->with('message', 'Demo online payment successful. Receipt generated.');
    }

    private function authorizeOrderAccess(?Order $order): void
    {
        abort_if(! $order, 404);

        if (auth()->check()) {
            abort_if((int) $order->customer_id !== (int) auth()->id(), 403);
            return;
        }

        $allowedIds = session('verified_order_ids', []);
        $lastOrderId = session('last_order_id');

        abort_if(! in_array($order->id, $allowedIds, true) && (int) $lastOrderId !== (int) $order->id, 403);
    }
}
