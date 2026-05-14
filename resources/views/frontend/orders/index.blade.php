@extends('frontend.layouts.app')

@section('title', 'My Orders')

@section('content')
<div class="zilo-mobile-page">
    @include('frontend.partials.topbar')

    <main class="front-flow-page">
        <section class="flow-heading">
            <span>My Orders</span>
            <h1>Track your orders</h1>
            <p>{{ auth()->check() ? 'Aapke account ke orders yahan show honge.' : 'Mobile number ya exact order number se order status, return eligibility aur return requests check karein.' }}</p>
        </section>

        @guest
            <form action="{{ route('frontend.orders.index') }}" method="GET" class="front-form-card order-lookup-form">
                <h2>Find Order</h2>
                <label>Mobile number
                    <input type="tel" name="mobile" value="{{ request('mobile') }}" placeholder="9876543210">
                </label>
                <label>Order number
                    <input type="text" name="order_number" value="{{ request('order_number') }}" placeholder="ORD-2026-00001">
                </label>
                <button type="submit" class="front-btn primary">Search Orders</button>
                <a href="{{ route('frontend.customer.login') }}" class="front-btn ghost">Login for My Orders</a>
            </form>
        @endguest

        @if(auth()->check() || request()->hasAny(['mobile', 'order_number']))
            <section class="order-card-list">
                @forelse($orders as $order)
                    <a href="{{ route('frontend.orders.show', $order) }}" class="customer-order-card">
                        <div>
                            <span>{{ $order->order_number }}</span>
                            <strong>Rs. {{ number_format((float) $order->total_amount, 0) }}</strong>
                        </div>
                        <div>
                            <small>{{ \App\Models\Order::ORDER_STATUSES[$order->order_status] ?? ucfirst($order->order_status) }}</small>
                            <small class="{{ $order->return_eligible ? 'good' : 'warn' }}">
                                {{ $order->return_eligible ? 'Return Available' : 'No Return' }}
                            </small>
                        </div>
                        <p>{{ $order->items->count() }} item{{ $order->items->count() === 1 ? '' : 's' }} | {{ optional($order->placed_at)->format('d M Y, h:i A') }}</p>
                    </a>
                @empty
                    <div class="empty-bag-state">
                        <i class="fas fa-receipt"></i>
                        <h2>No order found</h2>
                        <p>Mobile number ya order number check karke dobara search karein.</p>
                    </div>
                @endforelse
            </section>
        @endif
    </main>
</div>
@endsection
