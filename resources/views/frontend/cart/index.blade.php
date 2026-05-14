@extends('frontend.layouts.app')

@section('title', 'Your Bag')

@section('content')
<div class="zilo-mobile-page">
    @include('frontend.partials.topbar')

    <main class="front-flow-page">
        @if(session('message'))
            <div class="front-alert">{{ session('message') }}</div>
        @endif

        <section class="flow-heading">
            <span>Trial Bag</span>
            <h1>Your selected styles</h1>
            <p>Checkout par Try Cloth choose karoge to return band rahega. Normal buy me return available rahega.</p>
        </section>

        @if($products->isEmpty())
            <section class="empty-bag-state">
                <i class="fas fa-bag-shopping"></i>
                <h2>Bag empty hai</h2>
                <p>Women, Men ya Kids section se product add karke quick checkout start karein.</p>
                <a href="{{ route('frontend.home') }}" class="front-btn primary">Start Shopping</a>
            </section>
        @else
            <section class="cart-list">
                @foreach($products as $product)
                    @php
                        $image = $product->main_image['url'] ?? asset('assets/frontend/images/zilo-jpg/cat-dresses.jpg');
                    @endphp
                    <article class="cart-item">
                        <a href="{{ route('frontend.products.show', $product) }}">
                            <img src="{{ $image }}" alt="{{ $product->name }}">
                        </a>
                        <div>
                            <span>{{ $product->brand ?: optional($product->category)->name ?: 'StyleOne' }}</span>
                            <h2>{{ $product->name }}</h2>
                            <strong>Rs. {{ number_format($product->cart_total, 0) }}</strong>
                            <div class="cart-actions">
                                <form action="{{ route('frontend.cart.update', $product) }}" method="POST">
                                    @csrf
                                    @method('PATCH')
                                    <label>
                                        Qty
                                        <input type="number" name="quantity" value="{{ $product->cart_quantity }}" min="1" max="10">
                                    </label>
                                    <button type="submit">Update</button>
                                </form>
                                <form action="{{ route('frontend.cart.remove', $product) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="link-danger">Remove</button>
                                </form>
                            </div>
                        </div>
                    </article>
                @endforeach
            </section>

            <section class="checkout-summary-card">
                <div><span>Subtotal</span><strong>Rs. {{ number_format($subtotal, 0) }}</strong></div>
                <div><span>Delivery</span><strong>At checkout</strong></div>
                <a href="{{ route('frontend.checkout.index') }}" class="front-btn primary">Proceed to Buy</a>
            </section>
        @endif
    </main>
</div>
@endsection
