@extends('frontend.layouts.app')

@section('title', $product->name)

@section('content')
@php
    $mainImage = $product->main_image['url'] ?? asset('assets/frontend/images/zilo-jpg/cat-dresses.jpg');
    $price = $product->discount_price ?: $product->price;
@endphp
<div class="zilo-mobile-page">
    @include('frontend.partials.topbar')

    <section class="product-detail-media">
        <img src="{{ $mainImage }}" alt="{{ $product->name }}">
        @if($product->try_cloth_available)
            <span>Try First</span>
        @endif
    </section>

    <section class="product-detail-body">
        <p>{{ $product->brand ?: optional($product->category)->name ?: 'StyleOne' }}</p>
        <h1>{{ $product->name }}</h1>
        <div class="product-detail-price">
            <strong>Rs. {{ number_format((float) $price, 0) }}</strong>
            @if($product->discount_price)
                <del>Rs. {{ number_format((float) $product->price, 0) }}</del>
            @endif
        </div>
        <div class="product-badges">
            <span><i class="fas fa-truck-fast"></i> 60-120 min</span>
            <span><i class="fas fa-door-open"></i> Home trial</span>
            <span><i class="fas fa-rotate-left"></i> Return depends on Try Cloth</span>
        </div>
        <p class="product-description">{{ $product->description ?: $product->short_description ?: 'Trial-ready product available for quick delivery.' }}</p>

        @if($product->variants->count())
            <div class="variant-section">
                <h2>Available variants</h2>
                <div class="frontend-chip-rail">
                    @foreach($product->variants as $variant)
                        <button type="button">{{ $variant->size ?: 'Size' }} {{ $variant->color ? '/ ' . $variant->color : '' }}</button>
                    @endforeach
                </div>
            </div>
        @endif

        <div class="product-return-note">
            <strong>Buy rule</strong>
            <span>Checkout par Try Cloth select kiya to return nahi hoga. Try Cloth skip kiya to return available rahega.</span>
        </div>

        <form action="{{ route('frontend.cart.add', $product) }}" method="POST" class="product-add-form">
            @csrf
            <input type="hidden" name="quantity" value="1">
            <button type="submit" class="sticky-add-btn" data-add-bag>Add to Bag</button>
        </form>
    </section>

    <section class="frontend-product-section">
        <h2>Similar styles</h2>
        <div class="frontend-product-grid">
            @foreach($relatedProducts as $related)
                @include('frontend.partials.product-card', ['product' => $related])
            @endforeach
        </div>
    </section>
</div>
@endsection
