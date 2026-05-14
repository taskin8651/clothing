@extends('frontend.layouts.app')

@section('title', 'Customer Profile')

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

        <section class="customer-profile-hero">
            <div>
                <span>Customer</span>
                <h1>{{ $customer->name }}</h1>
                <p>{{ $customer->mobile ?: $customer->display_email }}</p>
            </div>
            <form action="{{ route('frontend.customer.logout') }}" method="POST">
                @csrf
                <button type="submit">Logout</button>
            </form>
        </section>

        <section class="profile-stat-grid">
            <div><strong>{{ $orders->count() }}</strong><span>Orders</span></div>
            <div><strong>{{ $customer->addresses->count() }}</strong><span>Addresses</span></div>
            <div><strong>{{ $wishlistProducts->count() }}</strong><span>Wishlist</span></div>
        </section>

        <section class="front-form-card">
            <h2>Add Address</h2>
            <form action="{{ route('frontend.customer.addresses.store') }}" method="POST" class="address-form-grid">
                @csrf
                <label>Name
                    <input type="text" name="name" value="{{ old('name', $customer->name) }}" required>
                </label>
                <label>Mobile
                    <input type="tel" name="mobile" value="{{ old('mobile', $customer->mobile) }}" required>
                </label>
                <label>Address
                    <textarea name="address" rows="2" required>{{ old('address') }}</textarea>
                </label>
                <div class="form-grid-2">
                    <label>City
                        <input type="text" name="city" value="{{ old('city', 'Mumbai') }}" required>
                    </label>
                    <label>Pincode
                        <input type="text" name="pincode" value="{{ old('pincode') }}" required>
                    </label>
                </div>
                <label>Area
                    <input type="text" name="area" value="{{ old('area') }}">
                </label>
                <label>Landmark
                    <input type="text" name="landmark" value="{{ old('landmark') }}">
                </label>
                <label class="inline-check">
                    <input type="checkbox" name="is_default" value="1">
                    <span>Make default</span>
                </label>
                <button type="submit" class="front-btn primary">Save Address</button>
            </form>
        </section>

        <section class="profile-section-card">
            <h2>Saved Addresses</h2>
            <div class="profile-list">
                @forelse($customer->addresses as $address)
                    <article class="profile-mini-card">
                        <span>{{ $address->is_default ? 'Default' : 'Address' }}</span>
                        <h3>{{ $address->name }} | {{ $address->mobile }}</h3>
                        <p>{{ $address->address }}, {{ $address->area }}, {{ $address->city }} - {{ $address->pincode }}</p>
                        <div>
                            @unless($address->is_default)
                                <form action="{{ route('frontend.customer.addresses.default', $address) }}" method="POST">
                                    @csrf
                                    <button type="submit">Set Default</button>
                                </form>
                            @endunless
                            <form action="{{ route('frontend.customer.addresses.delete', $address) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="danger">Delete</button>
                            </form>
                        </div>
                    </article>
                @empty
                    <p class="profile-empty">No saved address yet.</p>
                @endforelse
            </div>
        </section>

        <section class="profile-section-card">
            <h2>My Orders</h2>
            <div class="order-card-list">
                @forelse($orders as $order)
                    <a href="{{ route('frontend.orders.show', $order) }}" class="customer-order-card">
                        <div>
                            <span>{{ $order->order_number }}</span>
                            <strong>Rs. {{ number_format((float) $order->total_amount, 0) }}</strong>
                        </div>
                        <div>
                            <small>{{ \App\Models\Order::ORDER_STATUSES[$order->order_status] ?? ucfirst($order->order_status) }}</small>
                            <small class="{{ $order->return_eligible ? 'good' : 'warn' }}">{{ $order->return_eligible ? 'Return Available' : 'No Return' }}</small>
                        </div>
                    </a>
                @empty
                    <p class="profile-empty">Order history empty hai.</p>
                @endforelse
            </div>
        </section>

        <section class="profile-section-card">
            <div class="section-row-title">
                <h2>Wishlist</h2>
                <a href="{{ route('frontend.wishlist.index') }}">View all</a>
            </div>
            <div class="frontend-product-grid">
                @forelse($wishlistProducts as $product)
                    @include('frontend.partials.product-card', ['product' => $product, 'wishlistIds' => $wishlistProducts->pluck('id')->all()])
                @empty
                    <p class="profile-empty">Wishlist empty hai.</p>
                @endforelse
            </div>
        </section>

        <section class="profile-section-card">
            <h2>Recently Viewed</h2>
            <div class="frontend-product-grid">
                @forelse($recentProducts as $product)
                    @include('frontend.partials.product-card', ['product' => $product, 'wishlistIds' => $wishlistProducts->pluck('id')->all()])
                @empty
                    <p class="profile-empty">Recently viewed empty hai.</p>
                @endforelse
            </div>
        </section>

        <section class="profile-section-card">
            <h2>Return Requests</h2>
            <div class="profile-list">
                @forelse($returnRequests as $returnRequest)
                    <article class="profile-mini-card">
                        <span>{{ $returnRequest->return_number }}</span>
                        <h3>{{ $returnRequest->product_name }}</h3>
                        <p>{{ \App\Models\ReturnRequest::STATUSES[$returnRequest->status] ?? ucfirst($returnRequest->status) }} | Rs. {{ number_format((float) $returnRequest->refund_amount, 0) }}</p>
                    </article>
                @empty
                    <p class="profile-empty">Return requests empty hain.</p>
                @endforelse
            </div>
        </section>
    </main>
</div>
@endsection
