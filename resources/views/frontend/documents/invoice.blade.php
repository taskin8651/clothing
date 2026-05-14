@extends('frontend.layouts.app')

@section('title', $invoice->invoice_number)

@section('content')
<div class="zilo-mobile-page">
    @include('frontend.partials.topbar')

    <main class="front-flow-page document-page">
        @if(session('message'))
            <div class="front-alert">{{ session('message') }}</div>
        @endif

        <section class="document-hero">
            <span>Invoice</span>
            <h1>{{ $invoice->invoice_number }}</h1>
            <p>{{ optional($invoice->invoice_date)->format('d M Y') ?: now()->format('d M Y') }}</p>
            <button type="button" onclick="window.print()">Print</button>
        </section>

        <section class="document-card">
            <div class="document-two-col">
                <div>
                    <span>Bill To</span>
                    <strong>{{ $invoice->customer_name ?: 'Customer' }}</strong>
                    <p>{{ $invoice->customer_mobile }}</p>
                    <p>{{ $invoice->billing_address }}</p>
                </div>
                <div>
                    <span>Sold By</span>
                    <strong>{{ $invoice->shop_name ?: 'StyleOne' }}</strong>
                    <p>{{ $invoice->shop_mobile }}</p>
                    <p>{{ $invoice->shop_address }}</p>
                </div>
            </div>
        </section>

        <section class="document-card">
            <h2>Items</h2>
            <div class="document-items">
                @foreach($invoice->items as $item)
                    <div>
                        <span>{{ $item->product_name }} x {{ $item->quantity }}</span>
                        <strong>Rs. {{ number_format((float) $item->total, 0) }}</strong>
                    </div>
                @endforeach
            </div>
        </section>

        <section class="checkout-summary-card document-summary">
            <div><span>Subtotal</span><strong>Rs. {{ number_format((float) $invoice->subtotal, 0) }}</strong></div>
            <div><span>Discount</span><strong>Rs. {{ number_format((float) $invoice->discount_amount, 0) }}</strong></div>
            <div><span>Tax</span><strong>Rs. {{ number_format((float) $invoice->tax_amount, 0) }}</strong></div>
            <div><span>Delivery</span><strong>Rs. {{ number_format((float) $invoice->delivery_charge, 0) }}</strong></div>
            <div class="summary-total"><span>Total</span><strong>Rs. {{ number_format((float) $invoice->total_amount, 0) }}</strong></div>
            <div><span>Paid</span><strong>Rs. {{ number_format((float) $invoice->paid_amount, 0) }}</strong></div>
            <div><span>Due</span><strong>Rs. {{ number_format((float) $invoice->due_amount, 0) }}</strong></div>
            <div><span>Status</span><strong>{{ \App\Models\Invoice::STATUSES[$invoice->invoice_status] ?? ucfirst($invoice->invoice_status) }}</strong></div>
        </section>
    </main>
</div>
@endsection
