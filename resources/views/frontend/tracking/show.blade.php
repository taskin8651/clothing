@extends('frontend.layouts.app')

@section('title', $deliveryTracking->tracking_number)

@section('content')
@php
    $order = $deliveryTracking->order;
    $steps = [
        'pending' => 'Order placed',
        'assigned' => 'Rider assigned',
        'pickup_pending' => 'Pickup pending',
        'picked_up' => 'Picked up',
        'out_for_delivery' => 'Out for delivery',
        'delivered' => 'Delivered',
    ];
    $keys = array_keys($steps);
    $currentIndex = max(0, array_search($deliveryTracking->status, $keys, true) ?: 0);
@endphp
<div class="zilo-mobile-page">
    @include('frontend.partials.topbar')

    <main class="front-flow-page">
        <section class="order-success-hero compact">
            <span>Live Tracking</span>
            <h1>{{ $deliveryTracking->tracking_number }}</h1>
            <p>{{ \App\Models\DeliveryTracking::STATUSES[$deliveryTracking->status] ?? ucfirst($deliveryTracking->status) }}</p>
        </section>

        <section class="order-timeline-card">
            <h2>Delivery Timeline</h2>
            <div class="order-timeline">
                @foreach($steps as $status => $label)
                    @php $isActive = array_search($status, $keys, true) <= $currentIndex; @endphp
                    <div class="{{ $isActive ? 'active' : '' }}">
                        <i class="fas fa-circle"></i>
                        <span>{{ $label }}</span>
                    </div>
                @endforeach
            </div>
            @if($deliveryTracking->deliveryBoy)
                <p>Rider: {{ $deliveryTracking->deliveryBoy->name }} | {{ $deliveryTracking->deliveryBoy->mobile }}</p>
            @endif
        </section>

        <section class="tracking-address-card">
            <h2>Delivery Address</h2>
            <p>{{ $deliveryTracking->delivery_address }}</p>
            <span>{{ $deliveryTracking->area }}, {{ $deliveryTracking->city }} - {{ $deliveryTracking->pincode }}</span>
        </section>

        @if($order)
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
                            <p>{{ $item->quantity }} item{{ $item->quantity === 1 ? '' : 's' }}</p>
                        </div>
                    </article>
                @endforeach
            </section>
        @endif

        <section class="checkout-summary-card">
            <div><span>Order</span><strong>{{ $order?->order_number ?: '-' }}</strong></div>
            <div><span>Payment</span><strong>{{ $order ? (\App\Models\Order::PAYMENT_STATUSES[$order->payment_status] ?? ucfirst($order->payment_status)) : '-' }}</strong></div>
            <div><span>COD</span><strong>{{ $deliveryTracking->cod_collected ? 'Collected' : 'Pending' }}</strong></div>
        </section>
    </main>
</div>
@endsection
