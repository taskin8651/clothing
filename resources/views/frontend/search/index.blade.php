@extends('frontend.layouts.app')

@section('title', 'Search Products')

@section('content')
<div class="zilo-mobile-page">
    @include('frontend.partials.topbar')

    <main class="front-flow-page">
        @if(session('message'))
            <div class="front-alert">{{ session('message') }}</div>
        @endif

        <section class="flow-heading">
            <span>Search</span>
            <h1>Find your style</h1>
            <p>Name, brand, category aur price filters se products search karein.</p>
        </section>

        <form action="{{ route('frontend.search.index') }}" method="GET" class="front-form-card search-filter-card">
            <label>Search
                <input type="search" name="q" value="{{ request('q') }}" placeholder="Dress, kurta, heels...">
            </label>
            <div class="form-grid-2">
                <label>Category
                    <select name="category_id">
                        <option value="">All categories</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ (string) request('category_id') === (string) $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                </label>
                <label>Sort
                    <select name="sort">
                        <option value="">Featured</option>
                        <option value="latest" {{ request('sort') === 'latest' ? 'selected' : '' }}>Latest</option>
                        <option value="price_low" {{ request('sort') === 'price_low' ? 'selected' : '' }}>Price low-high</option>
                        <option value="price_high" {{ request('sort') === 'price_high' ? 'selected' : '' }}>Price high-low</option>
                    </select>
                </label>
            </div>
            <div class="form-grid-2">
                <label>Min price
                    <input type="number" name="min_price" value="{{ request('min_price') }}" min="0">
                </label>
                <label>Max price
                    <input type="number" name="max_price" value="{{ request('max_price') }}" min="0">
                </label>
            </div>
            <div class="search-checks">
                <label><input type="checkbox" name="try_cloth" value="1" {{ request('try_cloth') ? 'checked' : '' }}> Try Cloth</label>
                <label><input type="checkbox" name="return_available" value="1" {{ request('return_available') ? 'checked' : '' }}> Return available</label>
            </div>
            <button type="submit" class="front-btn primary">Apply Filters</button>
        </form>

        <section class="frontend-product-section compact-products">
            <div class="section-row-title">
                <h2>{{ $products->total() }} results</h2>
                <a href="{{ route('frontend.search.index') }}">Reset</a>
            </div>
            <div class="frontend-product-grid">
                @forelse($products as $product)
                    @include('frontend.partials.product-card', ['product' => $product, 'wishlistIds' => $wishlistIds])
                @empty
                    <div class="empty-state">No products found.</div>
                @endforelse
            </div>

            <div class="front-pagination">
                {{ $products->links() }}
            </div>
        </section>
    </main>
</div>
@endsection
