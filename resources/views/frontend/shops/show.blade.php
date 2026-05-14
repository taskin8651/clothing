@extends('frontend.layouts.app')

@section('title', $shop->shop_name)

@section('content')
<div class="zilo-mobile-page">
    @include('frontend.partials.topbar')

    <section class="front-page-hero shop-detail-hero">
        <span>{{ $shop->area ?: $shop->city ?: 'Store' }}</span>
        <h1>{{ $shop->shop_name }}</h1>
        <p>{{ $shop->address ?: 'Quick fashion delivery and doorstep trial available.' }}</p>
        <div class="hero-mini-meta">
            <strong><i class="fas fa-clock"></i> {{ $shop->opening_time ?: '07:00' }} - {{ $shop->closing_time ?: '23:00' }}</strong>
            <strong><i class="fas fa-location-dot"></i> {{ $shop->pincode ?: 'Mumbai' }}</strong>
        </div>
    </section>

    <section class="category-chip-section">
        <h2>Shop Categories</h2>
        <div class="frontend-chip-rail">
            @foreach($categories as $category)
                <a href="{{ route('frontend.categories.show', $category) }}">{{ $category->name }}</a>
            @endforeach
        </div>
    </section>

    <section class="frontend-product-section">
        <h2>{{ $shop->products->count() }} products</h2>
        <div class="frontend-product-grid">
            @forelse($shop->products as $product)
                @include('frontend.partials.product-card', ['product' => $product])
            @empty
                <div class="empty-state">No products in this shop yet.</div>
            @endforelse
        </div>
    </section>
</div>
@endsection
