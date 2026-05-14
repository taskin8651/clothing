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
        <form action="{{ route('frontend.shops.show', $shop) }}" method="GET" class="inline-filter-bar">
            <select name="category_id">
                <option value="">All</option>
                @foreach($categories as $category)
                    <option value="{{ $category->id }}" {{ (string) request('category_id') === (string) $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                @endforeach
            </select>
            <select name="sort">
                <option value="">Featured</option>
                <option value="latest" {{ request('sort') === 'latest' ? 'selected' : '' }}>Latest</option>
                <option value="price_low" {{ request('sort') === 'price_low' ? 'selected' : '' }}>Low-high</option>
                <option value="price_high" {{ request('sort') === 'price_high' ? 'selected' : '' }}>High-low</option>
            </select>
            <label><input type="checkbox" name="try_cloth" value="1" {{ request('try_cloth') ? 'checked' : '' }}> Try</label>
            <button type="submit">Filter</button>
        </form>
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
