@extends('frontend.layouts.app')

@section('title', 'Payment')

@section('content')
<div class="zilo-mobile-page">
    @include('frontend.partials.topbar')

    <main class="front-flow-page">
        <section class="document-hero">
            <span>Demo Payment</span>
            <h1>Rs. {{ number_format((float) $order->total_amount, 0) }}</h1>
            <p>{{ $order->order_number }}</p>
        </section>

        <section class="payment-demo-card">
            <i class="fas fa-credit-card"></i>
            <h2>Online payment placeholder</h2>
            <p>Abhi Razorpay/Stripe connect nahi hai. Is button se demo payment paid mark hoga aur receipt auto generate hogi.</p>
            <form action="{{ route('frontend.orders.payment.success', $order) }}" method="POST">
                @csrf
                <button type="submit" class="front-btn primary">Mark Demo Payment Paid</button>
            </form>
            <a href="{{ route('frontend.invoices.show', $invoice) }}" class="front-btn ghost">View Invoice</a>
        </section>
    </main>
</div>
@endsection
