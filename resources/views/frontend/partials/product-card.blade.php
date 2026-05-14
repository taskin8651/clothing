@php
    $fallbackImage = $fallbackImage ?? asset('assets/frontend/images/zilo-jpg/cat-dresses.jpg');
    $price = $product->discount_price ?: $product->price;
    $isWishlisted = auth()->check() && in_array($product->id, $wishlistIds ?? [], true);
@endphp

<article class="front-product-card">
    <a href="{{ route('frontend.products.show', $product) }}" class="front-product-image">
        <img src="{{ $product->main_image['url'] ?? $fallbackImage }}" alt="{{ $product->name }}">
        @if($product->try_cloth_available)
            <span>Try First</span>
        @endif
    </a>
    <form action="{{ route('frontend.wishlist.toggle', $product) }}" method="POST" class="wishlist-toggle-form">
        @csrf
        <button type="submit" title="Wishlist" class="{{ $isWishlisted ? 'active' : '' }}">
            <i class="{{ $isWishlisted ? 'fas' : 'far' }} fa-heart"></i>
        </button>
    </form>
    <div class="front-product-body">
        <p>{{ $product->brand ?: optional($product->category)->name ?: 'StyleOne' }}</p>
        <a href="{{ route('frontend.products.show', $product) }}">{{ $product->name }}</a>
        <div>
            <strong>Rs. {{ number_format((float) $price, 0) }}</strong>
            @if($product->discount_price)
                <del>Rs. {{ number_format((float) $product->price, 0) }}</del>
            @endif
        </div>
        <form action="{{ route('frontend.cart.add', $product) }}" method="POST" class="quick-add-form">
            @csrf
            <input type="hidden" name="quantity" value="1">
            <button type="submit" data-add-bag><i class="fas fa-plus"></i> Bag</button>
        </form>
    </div>
</article>
