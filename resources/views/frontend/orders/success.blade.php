@extends('frontend.layouts.app')

@section('title', 'Order Placed')

@section('content')
<div class="zilo-mobile-page">
    @include('frontend.partials.topbar')

    <main class="front-flow-page">
        <section class="order-success-hero">
            <i class="fas fa-circle-check"></i>
            <span>Order placed</span>
            <h1>{{ $order->order_number }}</h1>
            <p>Delivery update admin panel se track ho jayega.</p>
        </section>

        <section class="return-status-card {{ $order->return_eligible ? 'eligible' : 'locked' }}">
            <strong>
                @if($order->return_eligible)
                    Return available
                @else
                    No return on this order
                @endif
            </strong>
            <p>
                @if($order->try_cloth_selected)
                    Try Cloth select kiya gaya tha, isliye product return eligible nahi hai.
                @else
                    Try Cloth select nahi kiya gaya tha, isliye product return eligible hai.
                @endif
            </p>
        </section>

        <section class="cart-list">
            @foreach($order->items as $item)
                @php
                    $productImage = $item->product ? $item->product->main_image : null;
                    $image = $productImage['url'] ?? asset('assets/frontend/images/zilo-jpg/cat-dresses.jpg');
                @endphp
                <article class="cart-item">
                    <img src="{{ $image }}" alt="{{ $item->product_name }}">
                    <div>
                        <span>{{ $item->product_sku ?: 'StyleOne' }}</span>
                        <h2>{{ $item->product_name }}</h2>
                        <strong>Rs. {{ number_format((float) $item->total, 0) }}</strong>
                        <p>{{ $item->return_eligible ? 'Return eligible' : 'Return not available' }}</p>
                    </div>
                </article>
            @endforeach
        </section>

        <section class="checkout-summary-card">
            <div><span>Subtotal</span><strong>Rs. {{ number_format((float) $order->subtotal, 0) }}</strong></div>
            <div><span>Delivery</span><strong>Rs. {{ number_format((float) $order->delivery_charge, 0) }}</strong></div>
            <div class="summary-total"><span>Total</span><strong>Rs. {{ number_format((float) $order->total_amount, 0) }}</strong></div>
            <a href="{{ route('frontend.orders.show', $order) }}" class="front-btn primary">View Order</a>
            @if($order->deliveryTracking)
                <a href="{{ route('frontend.tracking.show', $order->deliveryTracking) }}" class="front-btn ghost">Track Delivery</a>
            @endif
            <a href="{{ route('frontend.home') }}" class="front-btn ghost">Continue Shopping</a>
        </section>
    </main>
</div>
@endsection
