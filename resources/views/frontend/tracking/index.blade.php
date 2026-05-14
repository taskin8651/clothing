@extends('frontend.layouts.app')

@section('title', 'Track Order')

@section('content')
<div class="zilo-mobile-page">
    @include('frontend.partials.topbar')

    <main class="front-flow-page">
        <section class="flow-heading">
            <span>Tracking</span>
            <h1>Track delivery</h1>
            <p>Order number, tracking number ya mobile se latest delivery status dekhein.</p>
        </section>

        <form action="{{ route('frontend.tracking.index') }}" method="GET" class="front-form-card order-lookup-form">
            <h2>Find Tracking</h2>
            <label>Order number
                <input type="text" name="order_number" value="{{ request('order_number') }}" placeholder="ORD-2026-00001">
            </label>
            <label>Tracking number
                <input type="text" name="tracking_number" value="{{ request('tracking_number') }}" placeholder="TRK-2026-00001">
            </label>
            <label>Mobile number
                <input type="tel" name="mobile" value="{{ request('mobile') }}" placeholder="9876543210">
            </label>
            <button type="submit" class="front-btn primary">Track Order</button>
        </form>

        @if(request()->hasAny(['order_number', 'tracking_number', 'mobile']))
            <section class="order-card-list">
                @forelse($orders as $order)
                    <a href="{{ $order->deliveryTracking ? route('frontend.tracking.show', $order->deliveryTracking) : route('frontend.orders.show', $order) }}" class="customer-order-card">
                        <div>
                            <span>{{ $order->order_number }}</span>
                            <strong>{{ $order->deliveryTracking?->tracking_number ?: 'Tracking pending' }}</strong>
                        </div>
                        <div>
                            <small>{{ \App\Models\Order::ORDER_STATUSES[$order->order_status] ?? ucfirst($order->order_status) }}</small>
                            <small class="{{ $order->deliveryTracking ? 'good' : 'warn' }}">
                                {{ $order->deliveryTracking ? (\App\Models\DeliveryTracking::STATUSES[$order->deliveryTracking->status] ?? $order->deliveryTracking->status) : 'Not created' }}
                            </small>
                        </div>
                        <p>{{ $order->customer_name ?: 'Customer' }} | {{ $order->city }} {{ $order->pincode }}</p>
                    </a>
                @empty
                    <div class="empty-bag-state">
                        <i class="fas fa-location-dot"></i>
                        <h2>No tracking found</h2>
                        <p>Details check karke dobara search karein.</p>
                    </div>
                @endforelse
            </section>
        @endif
    </main>
</div>
@endsection
