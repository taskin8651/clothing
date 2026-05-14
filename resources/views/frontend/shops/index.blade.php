@extends('frontend.layouts.app')

@section('title', 'Shops')

@section('content')
<div class="zilo-mobile-page">
    @include('frontend.partials.topbar')

    <section class="front-page-hero shop-hero">
        <span>Stores to Explore</span>
        <h1>Shop from nearby fashion stores</h1>
        <p>Try-first products, quick delivery zones and curated store collections.</p>
    </section>

    <section class="store-section">
        <h2>Available Stores</h2>
        <div class="frontend-shop-grid">
            @forelse($shops as $shop)
                <a href="{{ route('frontend.shops.show', $shop) }}" class="frontend-shop-card">
                    <img src="{{ asset('assets/frontend/images/zilo-jpg/store-last.jpg') }}" alt="{{ $shop->shop_name }}">
                    <div>
                        <span>{{ $shop->area ?: $shop->city ?: 'Mumbai' }}</span>
                        <h3>{{ $shop->shop_name }}</h3>
                        <p>{{ $shop->products_count }} products · {{ $shop->pincode ?: 'Serviceable' }}</p>
                    </div>
                </a>
            @empty
                <div class="empty-state">No shops available yet.</div>
            @endforelse
        </div>
    </section>

    <section class="frontend-product-section">
        <h2>Featured Products</h2>
        <div class="frontend-product-grid">
            @foreach($featuredProducts as $product)
                @include('frontend.partials.product-card', ['product' => $product])
            @endforeach
        </div>
    </section>
</div>
@endsection
