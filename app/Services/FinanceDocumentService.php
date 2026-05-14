<?php

namespace App\Services;

use App\Models\Invoice;
use App\Models\Order;
use App\Models\Payment;
use App\Models\Receipt;

class FinanceDocumentService
{
    public function generateInvoiceFromOrder(Order $order): Invoice
    {
        if ($order->latestInvoice) {
            return $order->latestInvoice;
        }

        $order->loadMissing(['customer', 'shop', 'items', 'payments', 'latestPayment']);
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
            'terms' => 'Products selected with Try Cloth are not eligible for return.',
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
                'tax_amount' => 0,
                'discount_amount' => 0,
                'total' => $item->total,
            ]);
        }

        return $invoice;
    }

    public function generateReceiptFromPayment(Payment $payment): ?Receipt
    {
        if ($payment->status !== 'paid') {
            return null;
        }

        $existing = $payment->receipts()->latest()->first();

        if ($existing) {
            return $existing;
        }

        $payment->loadMissing('order.latestInvoice');
        $order = $payment->order;
        $invoice = $order ? $this->generateInvoiceFromOrder($order) : null;

        $receipt = Receipt::create([
            'invoice_id' => optional($invoice)->id,
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
            'status' => 'paid',
            'received_from' => optional($order)->customer_name ?: optional(optional($order)->customer)->name,
            'notes' => 'Auto generated customer receipt.',
        ]);

        $this->syncInvoice($receipt);

        return $receipt;
    }

    public function syncInvoice(Receipt $receipt): void
    {
        if (! $receipt->invoice) {
            return;
        }

        $paid = $receipt->invoice->receipts()->where('status', 'paid')->sum('amount');
        $due = max(0, $receipt->invoice->total_amount - $paid);

        $receipt->invoice->update([
            'paid_amount' => $paid,
            'due_amount' => $due,
            'payment_status' => $due <= 0 ? 'paid' : $receipt->invoice->payment_status,
            'invoice_status' => $due <= 0 ? 'paid' : $receipt->invoice->invoice_status,
        ]);
    }
}
