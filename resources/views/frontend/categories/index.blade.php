@extends('frontend.layouts.app')

@section('title', 'Categories')

@section('content')
@php
    $imageMap = [
        'dresses' => 'cat-dresses.jpg',
        'footwear' => 'cat-footwear.jpg',
        'tops' => 'cat-tops.jpg',
        'jeans' => 'cat-jeans.jpg',
        'kurtas-sets' => 'cat-kurtas.jpg',
        'bags' => 'cat-bags.jpg',
        'jewellery' => 'cat-jewellery.jpg',
        'bottoms' => 'cat-skirts.jpg',
        'activewear' => 'cat-lingerie.jpg',
    ];
@endphp
<div class="zilo-mobile-page">
    @include('frontend.partials.topbar')

    <section class="front-page-hero category-hero">
        <span>Shop</span>
        <h1>All Categories</h1>
        <p>Explore dresses, footwear, bags, jewellery and more.</p>
    </section>

    <section class="shop-women-section">
        <h2>Shop Women</h2>
        <div class="shop-women-grid">
            @forelse($categories as $category)
                <a href="{{ route('frontend.categories.show', $category) }}">
                    <img src="{{ asset('assets/frontend/images/zilo-jpg/' . ($imageMap[$category->slug] ?? 'cat-dresses.jpg')) }}" alt="{{ $category->name }}">
                    <span>{{ $category->name }}</span>
                </a>
            @empty
                <div class="empty-state">No categories available yet.</div>
            @endforelse
        </div>
    </section>
</div>
@endsection
