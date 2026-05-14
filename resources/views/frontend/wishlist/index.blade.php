@extends('frontend.layouts.app')

@section('title', 'Wishlist')

@section('content')
<div class="zilo-mobile-page">
    @include('frontend.partials.topbar')

    <main class="front-flow-page">
        @if(session('message'))
            <div class="front-alert">{{ session('message') }}</div>
        @endif

        <section class="flow-heading">
            <span>Wishlist</span>
            <h1>Saved styles</h1>
            <p>Jo styles baad me buy karne hain, yahan save rahenge.</p>
        </section>

        <section class="frontend-product-section compact-products">
            <h2>{{ $wishlists->count() }} saved</h2>
            <div class="frontend-product-grid">
                @forelse($wishlists as $wishlist)
                    @if($wishlist->product)
                        @include('frontend.partials.product-card', [
                            'product' => $wishlist->product,
                            'wishlistIds' => $wishlists->pluck('product_id')->all(),
                        ])
                    @endif
                @empty
                    <div class="empty-bag-state">
                        <i class="far fa-heart"></i>
                        <h2>No saved styles</h2>
                        <p>Search ya product pages se heart tap karke products save karein.</p>
                        <a href="{{ route('frontend.search.index') }}" class="front-btn primary">Search Products</a>
                    </div>
                @endforelse
            </div>
        </section>
    </main>
</div>
@endsection
