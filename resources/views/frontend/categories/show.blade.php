@extends('frontend.layouts.app')

@section('title', $category->name)

@section('content')
<div class="zilo-mobile-page">
    @include('frontend.partials.topbar')

    <section class="front-page-hero category-detail-hero">
        <span>Category</span>
        <h1>{{ $category->name }}</h1>
        <p>Trial-ready products in {{ $category->name }}.</p>
    </section>

    <section class="category-chip-section">
        <h2>More Categories</h2>
        <div class="frontend-chip-rail">
            @foreach($siblingCategories as $sibling)
                <a href="{{ route('frontend.categories.show', $sibling) }}">{{ $sibling->name }}</a>
            @endforeach
        </div>
    </section>

    <section class="frontend-product-section">
        <h2>{{ $category->products->count() }} products</h2>
        <div class="frontend-product-grid">
            @forelse($category->products as $product)
                @include('frontend.partials.product-card', ['product' => $product])
            @empty
                <div class="empty-state">No products in this category yet.</div>
            @endforelse
        </div>
    </section>
</div>
@endsection
