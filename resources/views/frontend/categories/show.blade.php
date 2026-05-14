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
        <form action="{{ route('frontend.categories.show', $category) }}" method="GET" class="inline-filter-bar">
            <select name="sort">
                <option value="">Featured</option>
                <option value="latest" {{ request('sort') === 'latest' ? 'selected' : '' }}>Latest</option>
                <option value="price_low" {{ request('sort') === 'price_low' ? 'selected' : '' }}>Low-high</option>
                <option value="price_high" {{ request('sort') === 'price_high' ? 'selected' : '' }}>High-low</option>
            </select>
            <label><input type="checkbox" name="try_cloth" value="1" {{ request('try_cloth') ? 'checked' : '' }}> Try</label>
            <label><input type="checkbox" name="return_available" value="1" {{ request('return_available') ? 'checked' : '' }}> Return</label>
            <button type="submit">Filter</button>
        </form>
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
