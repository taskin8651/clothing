@extends('frontend.layouts.app')

@section('title', $order->order_number)

@section('content')
<div class="zilo-mobile-page">
    @include('frontend.partials.topbar')

    <main class="front-flow-page">
        @if(session('message'))
            <div class="front-alert">{{ session('message') }}</div>
        @endif

        @if($errors->any())
            <div class="front-alert danger">{{ $errors->first() }}</div>
        @endif

        <section class="order-success-hero compact">
            <span>Order Detail</span>
            <h1>{{ $order->order_number }}</h1>
            <p>{{ \App\Models\Order::ORDER_STATUSES[$order->order_status] ?? ucfirst($order->order_status) }}</p>
        </section>

        <section class="return-status-card {{ $order->return_eligible ? 'eligible' : 'locked' }}">
            <strong>{{ $order->return_eligible ? 'Return available' : 'No return on this order' }}</strong>
            <p>
                {{ $order->try_cloth_selected ? 'Try Cloth select kiya gaya tha, isliye return available nahi hai.' : 'Try Cloth select nahi kiya gaya tha, eligible items return request kar sakte hain.' }}
            </p>
        </section>

        <section class="order-timeline-card">
            <h2>Tracking</h2>
            @php
                $steps = [
                    'pending' => 'Order placed',
                    'confirmed' => 'Confirmed',
                    'packed' => 'Packed',
                    'out_for_delivery' => 'Out for delivery',
                    'delivered' => 'Delivered',
                ];
                $activeKeys = array_keys($steps);
                $currentIndex = max(0, array_search($order->order_status, $activeKeys, true) ?: 0);
            @endphp
            <div class="order-timeline">
                @foreach($steps as $status => $label)
                    @php $isActive = array_search($status, $activeKeys, true) <= $currentIndex; @endphp
                    <div class="{{ $isActive ? 'active' : '' }}">
                        <i class="fas fa-circle"></i>
                        <span>{{ $label }}</span>
                    </div>
                @endforeach
            </div>
            @if($order->deliveryTracking)
                <p>
                    Tracking: {{ $order->deliveryTracking->tracking_number }} | {{ \App\Models\DeliveryTracking::STATUSES[$order->deliveryTracking->status] ?? $order->deliveryTracking->status }}
                    <a href="{{ route('frontend.tracking.show', $order->deliveryTracking) }}">View live</a>
                </p>
            @endif
        </section>

        <section class="cart-list">
            @foreach($order->items as $item)
                @php
                    $productImage = $item->product ? $item->product->main_image : null;
                    $image = $productImage['url'] ?? asset('assets/frontend/images/zilo-jpg/cat-dresses.jpg');
                    $returnRequest = $order->returnRequests->firstWhere('order_item_id', $item->id);
                    $canReturn = $item->return_eligible && ! $item->try_cloth_selected && ! $returnRequest;
                @endphp
                <article class="order-item-return-card">
                    <div class="cart-item">
                        <img src="{{ $image }}" alt="{{ $item->product_name }}">
                        <div>
                            <span>{{ $item->product_sku ?: 'StyleOne' }}</span>
                            <h2>{{ $item->product_name }}</h2>
                            <strong>Rs. {{ number_format((float) $item->total, 0) }}</strong>
                            <p>{{ $item->return_eligible && ! $item->try_cloth_selected ? 'Return eligible' : 'Return not available' }}</p>
                        </div>
                    </div>

                    @if($returnRequest)
                        <div class="return-request-chip">
                            Return requested: {{ \App\Models\ReturnRequest::STATUSES[$returnRequest->status] ?? ucfirst($returnRequest->status) }}
                        </div>
                    @elseif($canReturn)
                        <form action="{{ route('frontend.orders.returns.store', [$order, $item]) }}" method="POST" class="return-mini-form">
                            @csrf
                            <label>Reason
                                <select name="reason" required>
                                    <option value="">Select reason</option>
                                    <option value="Size issue">Size issue</option>
                                    <option value="Quality issue">Quality issue</option>
                                    <option value="Wrong item delivered">Wrong item delivered</option>
                                    <option value="Changed mind">Changed mind</option>
                                </select>
                            </label>
                            <label>Description
                                <textarea name="description" rows="2" placeholder="Optional detail"></textarea>
                            </label>
                            <button type="submit" class="front-btn ghost">Request Return</button>
                        </form>
                    @else
                        <div class="return-request-chip locked">Try Cloth selected tha, return available nahi hai.</div>
                    @endif
                </article>
            @endforeach
        </section>

        <section class="checkout-summary-card">
            <div><span>Payment</span><strong>{{ \App\Models\Order::PAYMENT_METHODS[$order->payment_method] ?? strtoupper($order->payment_method) }}</strong></div>
            <div><span>Status</span><strong>{{ \App\Models\Order::PAYMENT_STATUSES[$order->payment_status] ?? ucfirst($order->payment_status) }}</strong></div>
            <div><span>Subtotal</span><strong>Rs. {{ number_format((float) $order->subtotal, 0) }}</strong></div>
            <div><span>Delivery</span><strong>Rs. {{ number_format((float) $order->delivery_charge, 0) }}</strong></div>
            <div class="summary-total"><span>Total</span><strong>Rs. {{ number_format((float) $order->total_amount, 0) }}</strong></div>
        </section>
    </main>
</div>
@endsection
