@extends('frontend.layouts.app')

@section('title', $receipt->receipt_number)

@section('content')
<div class="zilo-mobile-page">
    @include('frontend.partials.topbar')

    <main class="front-flow-page document-page">
        @if(session('message'))
            <div class="front-alert">{{ session('message') }}</div>
        @endif

        <section class="document-hero paid">
            <span>Receipt</span>
            <h1>{{ $receipt->receipt_number }}</h1>
            <p>{{ optional($receipt->receipt_date)->format('d M Y') ?: now()->format('d M Y') }}</p>
            <button type="button" onclick="window.print()">Print</button>
        </section>

        <section class="document-card">
            <div class="document-two-col">
                <div>
                    <span>Received From</span>
                    <strong>{{ $receipt->received_from ?: optional($receipt->customer)->name ?: optional($receipt->order)->customer_name }}</strong>
                    <p>{{ optional($receipt->order)->customer_mobile }}</p>
                </div>
                <div>
                    <span>Reference</span>
                    <strong>{{ optional($receipt->order)->order_number ?: '-' }}</strong>
                    <p>Invoice: {{ optional($receipt->invoice)->invoice_number ?: '-' }}</p>
                </div>
            </div>
        </section>

        <section class="checkout-summary-card document-summary">
            <div><span>Amount</span><strong>Rs. {{ number_format((float) $receipt->amount, 0) }}</strong></div>
            <div><span>Method</span><strong>{{ strtoupper($receipt->payment_method ?: '-') }}</strong></div>
            <div><span>Gateway</span><strong>{{ $receipt->payment_gateway ?: '-' }}</strong></div>
            <div><span>Transaction</span><strong>{{ $receipt->transaction_id ?: '-' }}</strong></div>
            <div><span>Status</span><strong>{{ \App\Models\Receipt::STATUSES[$receipt->status] ?? ucfirst($receipt->status) }}</strong></div>
        </section>
    </main>
</div>
@endsection
