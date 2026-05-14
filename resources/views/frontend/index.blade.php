@extends('frontend.layouts.app')

@section('title', 'Clothing in 60 Minutes')

@section('content')
@php
    $assetImage = asset('assets/frontend/images/fashion-store.jpg');
    $ziloAsset = fn ($name) => asset('assets/frontend/images/zilo-jpg/' . $name);
    $money = fn ($amount) => 'Rs. ' . number_format((float) $amount, 0);
    $fallbackCategories = collect([
        ['name' => 'Women', 'icon' => 'fa-person-dress', 'tag' => 'Kurtas, dresses, denim'],
        ['name' => 'Men', 'icon' => 'fa-shirt', 'tag' => 'Shirts, tees, trousers'],
        ['name' => 'Kids', 'icon' => 'fa-child-reaching', 'tag' => 'Festive, daily wear'],
        ['name' => 'Footwear', 'icon' => 'fa-shoe-prints', 'tag' => 'Sneakers, flats, heels'],
        ['name' => 'Bags', 'icon' => 'fa-bag-shopping', 'tag' => 'Totes, sling bags'],
        ['name' => 'Jewellery', 'icon' => 'fa-gem', 'tag' => 'Daily shine'],
    ]);
    $fallbackProducts = collect([
        ['name' => 'Linen Co-ord Set', 'brand' => 'New Season', 'price' => 1899, 'discount_price' => 1399, 'tag' => 'Try at home'],
        ['name' => 'Relaxed Oxford Shirt', 'brand' => 'Office Ready', 'price' => 1299, 'discount_price' => 999, 'tag' => '60 min'],
        ['name' => 'Printed Kurta Set', 'brand' => 'Festive Picks', 'price' => 2299, 'discount_price' => 1699, 'tag' => 'COD'],
        ['name' => 'Weekend Denim Jacket', 'brand' => 'Street Edit', 'price' => 2499, 'discount_price' => 1899, 'tag' => 'Return easy'],
    ]);
    $fallbackSections = collect([
        ['title' => 'Try First, Buy Later', 'subtitle' => 'Choose 3 looks, keep what fits, return the rest instantly.', 'type' => 'hero'],
        ['title' => 'Wedding Guest Edit', 'subtitle' => 'Ethnic sets, jewellery and heels delivered today.', 'type' => 'collection'],
        ['title' => 'Office in 2 Hours', 'subtitle' => 'Crisp shirts, trousers and bags for urgent plans.', 'type' => 'banner'],
    ]);
    $genderTabs = collect([
        ['label' => 'Women', 'icon' => 'fa-venus', 'active' => true],
        ['label' => 'Men', 'icon' => 'fa-mars', 'active' => false],
        ['label' => 'Kids', 'icon' => 'fa-child', 'active' => false],
        ['label' => 'Curve', 'icon' => 'fa-ruler-combined', 'active' => false],
    ]);
    $brandNames = collect(['Adidas', 'Biba', 'Global Desi', 'Libas', 'Metro', 'Mochi', 'Puma', 'Van Heusen', 'Zouk', 'AND', 'Suta', 'Zivame']);
    $occasionEdits = collect([
        ['title' => 'Shaadi Tonight', 'copy' => 'Anarkalis, jewellery, heels', 'tone' => 'rose'],
        ['title' => 'Office Rush', 'copy' => 'Shirts, trousers, laptop bags', 'tone' => 'blue'],
        ['title' => 'Weekend Plans', 'copy' => 'Denim, dresses, sneakers', 'tone' => 'green'],
        ['title' => 'Festive Morning', 'copy' => 'Kurta sets and dupattas', 'tone' => 'yellow'],
    ]);
    $sizeTiles = collect(['XS', 'S', 'M', 'L', 'XL', 'XXL', '3XL', 'Free Size']);
    $bodyGuides = collect([
        ['title' => 'Pear Shape', 'copy' => 'A-line fits, flared kurtas'],
        ['title' => 'Hourglass', 'copy' => 'Wrap dresses, belted sets'],
        ['title' => 'Tall Fit', 'copy' => 'Longline shirts, wide pants'],
        ['title' => 'Petite', 'copy' => 'Cropped layers, high waist'],
    ]);
    $seoFaqs = collect([
        ['q' => 'Can I try clothes before payment?', 'a' => 'Yes. Select Try First products, try at doorstep, and pay only for items you keep.'],
        ['q' => 'How fast is delivery?', 'a' => 'Serviceable zones can be configured from admin, usually 60 to 120 minutes.'],
        ['q' => 'Can I return instantly?', 'a' => 'During home trial you can hand back items immediately to the delivery partner.'],
        ['q' => 'Is COD available?', 'a' => 'COD is available zone-wise and can be enabled or disabled from admin delivery zones.'],
    ]);
    $ziloBanners = collect([
        ['image' => $ziloAsset('desi.jpg'), 'title' => 'Make it Desi'],
        ['image' => $ziloAsset('summer.jpg'), 'title' => 'Cool for Summer'],
        ['image' => $ziloAsset('plans.jpg'), 'title' => 'Plans Tonight'],
        ['image' => $ziloAsset('new.jpg'), 'title' => 'New Launches'],
    ]);
    $carouselSlides = collect([
        ['image' => $ziloAsset('carousel-1.jpg'), 'title' => 'Effortless ease: unified co-ords.', 'copy' => 'Up to 70% off'],
        ['image' => $ziloAsset('carousel-2.jpg'), 'title' => 'Summer styles, delivered fast.', 'copy' => 'Try first buy later'],
        ['image' => $ziloAsset('carousel-3.jpg'), 'title' => 'Plans tonight? We got you.', 'copy' => '60 min delivery'],
    ]);
    $wearEdits = collect([
        ['title' => 'MAKE IT DESI', 'image' => $ziloAsset('women-desi.jpg')],
        ['title' => 'COOL FOR SUMMER', 'image' => $ziloAsset('women-summer.jpg')],
        ['title' => 'PLANS TONIGHT?', 'image' => $ziloAsset('women-plans.jpg')],
        ['title' => 'OUTFIT FILLER', 'image' => $ziloAsset('women-office.jpg')],
    ]);
    $shopWomenTiles = collect([
        ['title' => 'Dresses', 'image' => $ziloAsset('cat-dresses.jpg')],
        ['title' => 'Footwear', 'image' => $ziloAsset('cat-footwear.jpg')],
        ['title' => 'Tops', 'image' => $ziloAsset('cat-tops.jpg')],
        ['title' => 'Jeans', 'image' => $ziloAsset('cat-jeans.jpg')],
        ['title' => 'Kurtas & Sets', 'image' => $ziloAsset('cat-kurtas.jpg')],
        ['title' => 'Bags', 'image' => $ziloAsset('cat-bags.jpg')],
        ['title' => 'Jewellery', 'image' => $ziloAsset('cat-jewellery.jpg')],
        ['title' => 'Skirts', 'image' => $ziloAsset('cat-skirts.jpg')],
        ['title' => 'Lingerie', 'image' => $ziloAsset('cat-lingerie.jpg')],
    ]);
    $moods = collect([
        ['title' => 'GAME READY', 'image' => $ziloAsset('mood-game.jpg')],
        ['title' => 'HOLIDAY DRIP', 'image' => $ziloAsset('mood-holiday.jpg')],
        ['title' => 'URBAN EASE', 'image' => $ziloAsset('mood-urban.jpg')],
        ['title' => 'NIGHT FEVER', 'image' => $ziloAsset('mood-night.jpg')],
    ]);
    $summerPicks = collect([
        ['title' => 'Strappy Styles', 'image' => $ziloAsset('summer-strap.jpg')],
        ['title' => 'Denim Shorts', 'image' => $ziloAsset('summer-denim.jpg')],
        ['title' => 'Wide Leg Fits', 'image' => $ziloAsset('summer-wide.jpg')],
    ]);
    $brandCards = collect([
        ['title' => 'Bata', 'image' => $ziloAsset('brand-1.jpg')],
        ['title' => 'Palmonas', 'image' => $ziloAsset('brand-2.jpg')],
        ['title' => 'Puma', 'image' => $ziloAsset('brand-3.jpg')],
    ]);
    $sportsCards = collect([
        ['title' => 'Bengaluru', 'image' => $ziloAsset('sports-1.jpg')],
        ['title' => 'Delhi', 'image' => $ziloAsset('sports-2.jpg')],
        ['title' => 'Chennai', 'image' => $ziloAsset('sports-3.jpg')],
    ]);
    $stores = collect([
        ['title' => 'The Last Minute Store', 'image' => $ziloAsset('store-last.jpg')],
        ['title' => 'The Shoe Gallery', 'image' => $ziloAsset('store-shoe.jpg')],
    ]);
    $alist = collect([
        ['title' => 'Bata', 'image' => $ziloAsset('alist-1.jpg')],
        ['title' => 'Palmonas', 'image' => $ziloAsset('alist-2.jpg')],
        ['title' => 'Puma', 'image' => $ziloAsset('alist-3.jpg')],
    ]);
    $mall = collect([
        ['title' => 'Inc.5', 'image' => $ziloAsset('mall-1.jpg')],
        ['title' => 'Pepe Jeans', 'image' => $ziloAsset('mall-2.jpg')],
        ['title' => 'JUST IN TIME', 'image' => $ziloAsset('mall-3.jpg')],
    ]);
    $couponCards = collect([
        ['title' => 'First Order Code APP50', 'image' => $ziloAsset('coupon.jpg')],
        ['title' => 'Code DRESS15', 'image' => $ziloAsset('coupon.jpg')],
        ['title' => 'Cart Offer', 'image' => $ziloAsset('coupon.jpg')],
    ]);
    $sectionGroups = $sections->groupBy('type');
    $sectionCards = function ($type, $fallback) use ($sectionGroups) {
        $items = $sectionGroups->get($type, collect());

        if ($items->isEmpty()) {
            return $fallback;
        }

        return $items->map(fn ($section) => [
            'title' => $section->title,
            'copy' => $section->subtitle,
            'image' => $section->image_url,
        ])->values();
    };
    $categoryImageMap = [
        'dresses' => $ziloAsset('cat-dresses.jpg'),
        'footwear' => $ziloAsset('cat-footwear.jpg'),
        'tops' => $ziloAsset('cat-tops.jpg'),
        'jeans' => $ziloAsset('cat-jeans.jpg'),
        'kurtas-sets' => $ziloAsset('cat-kurtas.jpg'),
        'bags' => $ziloAsset('cat-bags.jpg'),
        'jewellery' => $ziloAsset('cat-jewellery.jpg'),
        'bottoms' => $ziloAsset('cat-skirts.jpg'),
        'activewear' => $ziloAsset('cat-lingerie.jpg'),
        'innerwear' => $ziloAsset('women-office.jpg'),
        'sarees' => $ziloAsset('women-desi.jpg'),
        'watches' => $ziloAsset('mall-3.jpg'),
    ];

    $carouselSlides = $sectionCards('carousel', $carouselSlides);
    $wearEdits = $sectionCards('wear_edit', $wearEdits);
    $moods = $sectionCards('mood', $moods);
    $couponCards = $sectionCards('coupon', $couponCards);
    $summerPicks = $sectionCards('summer_pick', $summerPicks);
    $brandCards = $sectionCards('brand_card', $brandCards);
    $sportsCards = $sectionCards('sports_card', $sportsCards);
    $stores = $sectionCards('store_card', $stores);
    $alist = $sectionCards('alist_pick', $alist);
    $mall = $sectionCards('mall_pick', $mall);

    if ($categories->isNotEmpty()) {
        $shopWomenTiles = $categories->take(12)->map(function ($category) use ($categoryImageMap, $ziloAsset) {
            return [
                'title' => $category->name,
                'image' => $categoryImageMap[$category->slug] ?? $ziloAsset('cat-dresses.jpg'),
            ];
        })->values();
    }
@endphp

<div class="zilo-mobile-page" id="home">
    <header class="zilo-mobile-header">
        <div class="shopping-tabs">
            <span>Shopping for</span>
            <nav>
                <button type="button" class="active">WOMEN</button>
                <button type="button">MEN</button>
                <button type="button">KIDS</button>
            </nav>
        </div>
        <div class="delivery-summary">
            <button type="button">Location: Mumbai, Maharashtra, In... <i class="fas fa-chevron-down"></i></button>
            <strong><i class="far fa-clock"></i> 7 AM - 8 AM</strong>
            <span>Tomorrow</span>
        </div>
    </header>

    <section class="wear-section">
        <h1>Nothing To Wear?</h1>
        <div class="wear-rail">
            @foreach($wearEdits as $edit)
                <a href="#shop">
                    <img src="{{ $edit['image'] }}" alt="{{ $edit['title'] }}">
                    <strong>{{ $edit['title'] }}</strong>
                </a>
            @endforeach
        </div>
    </section>

    <section class="front-carousel" aria-label="Featured collections">
        <div class="carousel-track" id="frontCarousel">
            @foreach($carouselSlides as $slide)
                <article class="carousel-slide {{ $loop->first ? 'active' : '' }}">
                    <img src="{{ $slide['image'] }}" alt="{{ $slide['title'] }}">
                    <div>
                        <span>StyleOne edit</span>
                        <h2>{{ $slide['title'] }}</h2>
                        <p>{{ $slide['copy'] }}</p>
                    </div>
                </article>
            @endforeach
        </div>
        <button type="button" class="carousel-btn prev" data-carousel-prev aria-label="Previous slide">
            <i class="fas fa-chevron-left"></i>
        </button>
        <button type="button" class="carousel-btn next" data-carousel-next aria-label="Next slide">
            <i class="fas fa-chevron-right"></i>
        </button>
        <div class="carousel-dots" aria-label="Carousel dots">
            @foreach($carouselSlides as $slide)
                <button type="button" class="{{ $loop->first ? 'active' : '' }}" data-carousel-dot="{{ $loop->index }}" aria-label="Go to slide {{ $loop->iteration }}"></button>
            @endforeach
        </div>
    </section>

    <section class="zilo-coupon-strip" aria-label="Coupons">
        @foreach($couponCards as $coupon)
            <img src="{{ $coupon['image'] }}" alt="{{ $coupon['title'] }}">
        @endforeach
    </section>

    <section class="shop-women-section" id="shop">
        <h2>Shop Women</h2>
        <div class="shop-women-grid">
            @foreach($shopWomenTiles as $tile)
                <a href="#products">
                    <img src="{{ $tile['image'] }}" alt="{{ $tile['title'] }}">
                    <span>{{ $tile['title'] }}</span>
                </a>
            @endforeach
        </div>
    </section>

    <section class="mood-section">
        <h2>Pick Your Mood</h2>
        <div class="mood-grid">
            @foreach($moods as $mood)
                <a href="#products">
                    <img src="{{ $mood['image'] }}" alt="{{ $mood['title'] }}">
                    <span>{{ $mood['title'] }}</span>
                    <i class="fas fa-arrow-up-right-from-square"></i>
                </a>
            @endforeach
        </div>
    </section>

    <section class="summer-refresh">
        <img class="summer-main" src="{{ $ziloAsset('summer-main.jpg') }}" alt="Summer wardrobe refresh">
        <div class="summer-copy">
            <h2>Summer Wardrobe Refresh</h2>
            <p>The must-have summer styles</p>
        </div>
        <div class="summer-picks">
            @foreach($summerPicks as $pick)
                <a href="#products">
                    <img src="{{ $pick['image'] }}" alt="{{ $pick['title'] }}">
                    <span>{{ $pick['title'] }}</span>
                </a>
            @endforeach
        </div>
    </section>

    <section class="dark-brand-section">
        <h2>Brands You Love</h2>
        <div class="dark-card-rail">
            @foreach($brandCards as $brand)
                <a href="#products">
                    <img src="{{ $brand['image'] }}" alt="{{ $brand['title'] }}">
                    <span>{{ $brand['title'] }}</span>
                </a>
            @endforeach
        </div>
    </section>

    <section class="sports-section">
        <div class="sports-grid">
            @foreach($sportsCards as $card)
                <a href="#products">
                    <img src="{{ $card['image'] }}" alt="{{ $card['title'] }}">
                    <span>{{ strtoupper($card['title']) }}</span>
                </a>
            @endforeach
        </div>
        <a href="#products" class="wide-banner">
            <img src="{{ $ziloAsset('brands.jpg') }}" alt="Team merchandise">
            <i class="fas fa-arrow-up-right-from-square"></i>
        </a>
    </section>

    <section class="store-section">
        <h2>Stores to Explore</h2>
        <div class="store-grid">
            @foreach($stores as $store)
                <a href="#products">
                    <img src="{{ $store['image'] }}" alt="{{ $store['title'] }}">
                </a>
            @endforeach
        </div>
    </section>

    <section class="alist-section">
        <h2>A-LIST PICKS</h2>
        <div class="alist-grid">
            @foreach($alist as $pick)
                <a href="#products">
                    <img src="{{ $pick['image'] }}" alt="{{ $pick['title'] }}">
                    <span>{{ $pick['title'] }}</span>
                </a>
            @endforeach
        </div>
    </section>

    <section class="director-section">
        <h2>CURATED BY OUR STYLE DIRECTOR</h2>
        <a href="#products" class="large-store-card">
            <img src="{{ $ziloAsset('director.jpg') }}" alt="Curated by style director">
        </a>
    </section>

    <section class="store-stack">
        <h2>The Summer Travel Store</h2>
        <a href="#products" class="large-store-card">
            <img src="{{ $ziloAsset('travel.jpg') }}" alt="Summer travel store">
            <i class="fas fa-arrow-up-right-from-square"></i>
        </a>
        <h2>Style Director Store</h2>
        <a href="#products" class="large-store-card">
            <img src="{{ $ziloAsset('style-director.jpg') }}" alt="Style director store">
            <i class="fas fa-arrow-up-right-from-square"></i>
        </a>
        <h2>The Shoe Gallery Store</h2>
        <a href="#products" class="large-store-card">
            <img src="{{ $ziloAsset('shoe-gallery.jpg') }}" alt="Shoe gallery store">
            <i class="fas fa-arrow-up-right-from-square"></i>
        </a>
    </section>

    <section class="mall-section">
        <a href="#products" class="wide-banner red-banner">
            <img src="{{ $ziloAsset('cannes.jpg') }}" alt="Cannes reimagined">
            <i class="fas fa-arrow-up-right-from-square"></i>
        </a>
        <h2>Mall to Home</h2>
        <div class="alist-grid">
            @foreach($mall as $item)
                <a href="#products">
                    <img src="{{ $item['image'] }}" alt="{{ $item['title'] }}">
                    <span>{{ $item['title'] }}</span>
                </a>
            @endforeach
        </div>
        <h2>The Last Minute Store</h2>
        <a href="#products" class="large-store-card">
            <img src="{{ $ziloAsset('last-store.jpg') }}" alt="Last minute store">
            <i class="fas fa-arrow-up-right-from-square"></i>
        </a>
    </section>

    <main>
        <section class="zilo-banner-grid">
            @foreach($ziloBanners as $banner)
                <a href="#products" class="zilo-image-banner">
                <img src="{{ $banner['image'] }}" alt="{{ $banner['title'] }}">
                </a>
            @endforeach
        </section>

        <section class="zilo-full-banner">
            <a href="#products" class="zilo-image-banner">
                <img src="{{ $ziloAsset('shaadi.jpg') }}" alt="Shaadi closet">
            </a>
        </section>

        <section class="coupon-section">
            <div class="section-head compact">
                <div>
                    <span>Coupons</span>
                    <h2>Deals you can use today</h2>
                </div>
            </div>
            <div class="coupon-rail">
                <img src="{{ $ziloAsset('coupon.jpg') }}" alt="Extra discount coupon">
                <img src="{{ $ziloAsset('coupon.jpg') }}" alt="Extra discount coupon">
                <img src="{{ $ziloAsset('coupon.jpg') }}" alt="Extra discount coupon">
            </div>
        </section>

        <section class="front-section" id="shop">
            <div class="section-head">
                <div>
                    <span>Shop by category</span>
                    <h2>Pick your department</h2>
                </div>
                <a href="#search">View all</a>
            </div>

            <div class="category-rail">
                @forelse($categories as $category)
                    <a class="category-tile" href="#products">
                        <span><i class="fas fa-layer-group"></i></span>
                        <strong>{{ $category->name }}</strong>
                        <small>{{ $category->products_count ?? 'New drops' }}</small>
                    </a>
                @empty
                    @foreach($fallbackCategories as $category)
                        <a class="category-tile" href="#products">
                            <span><i class="fas {{ $category['icon'] }}"></i></span>
                            <strong>{{ $category['name'] }}</strong>
                            <small>{{ $category['tag'] }}</small>
                        </a>
                    @endforeach
                @endforelse
            </div>
        </section>

        <section class="zilo-full-banner">
            <a href="#products" class="zilo-image-banner">
                <img src="{{ $ziloAsset('women.jpg') }}" alt="Shop women">
            </a>
        </section>

        <section class="front-section" id="brands">
            <div class="section-head">
                <div>
                    <span>Featured brands</span>
                    <h2>Brands you love, delivered today</h2>
                </div>
                <a href="#products">Shop brands</a>
            </div>

            <div class="brand-rail">
                @foreach($brandNames as $brand)
                    <a href="#products" class="brand-chip">
                        <strong>{{ substr($brand, 0, 1) }}</strong>
                        <span>{{ $brand }}</span>
                    </a>
                @endforeach
            </div>
        </section>

        <section class="zilo-full-banner">
            <a href="#brands" class="zilo-image-banner">
                <img src="{{ $ziloAsset('brands.jpg') }}" alt="Brands you love">
            </a>
        </section>

        <section class="front-section" id="occasions">
            <div class="section-head">
                <div>
                    <span>Shop by occasion</span>
                    <h2>Last-minute looks that still feel planned</h2>
                </div>
            </div>

            <div class="occasion-grid">
                @foreach($occasionEdits as $edit)
                    <a href="#products" class="occasion-card {{ $edit['tone'] }}">
                        <img src="{{ $assetImage }}" alt="{{ $edit['title'] }}">
                        <div>
                            <span>Ready in 2 hrs</span>
                            <h3>{{ $edit['title'] }}</h3>
                            <p>{{ $edit['copy'] }}</p>
                        </div>
                    </a>
                @endforeach
            </div>
        </section>

        <section class="front-section" id="offers">
            <div class="section-head">
                <div>
                    <span>Today on StyleOne</span>
                    <h2>Fast picks for every plan</h2>
                </div>
            </div>

            <div class="campaign-grid">
                @forelse($sections->take(3) as $section)
                    <a class="campaign-card {{ $loop->first ? 'large' : '' }}" href="{{ $section->link_url ?: '#products' }}">
                        <img src="{{ $section->image_url ?: $assetImage }}" alt="{{ $section->title }}">
                        <div>
                            <span>{{ ucfirst(str_replace('_', ' ', $section->type)) }}</span>
                            <h3>{{ $section->title }}</h3>
                            <p>{{ $section->subtitle ?: 'Fresh styles curated for quick delivery.' }}</p>
                        </div>
                    </a>
                @empty
                    @foreach($fallbackSections as $section)
                        <a class="campaign-card {{ $loop->first ? 'large' : '' }}" href="#products">
                            <img src="{{ $assetImage }}" alt="{{ $section['title'] }}">
                            <div>
                                <span>{{ ucfirst($section['type']) }}</span>
                                <h3>{{ $section['title'] }}</h3>
                                <p>{{ $section['subtitle'] }}</p>
                            </div>
                        </a>
                    @endforeach
                @endforelse
            </div>
        </section>

        <section class="front-section">
            <div class="section-head">
                <div>
                    <span>Personal fit</span>
                    <h2>Shop by size and fit guide</h2>
                </div>
            </div>

            <div class="fit-layout">
                <div class="size-strip">
                    @foreach($sizeTiles as $size)
                        <a href="#products">{{ $size }}</a>
                    @endforeach
                </div>
                <div class="body-guide-grid">
                    @foreach($bodyGuides as $guide)
                        <a href="#products">
                            <i class="fas fa-ruler"></i>
                            <strong>{{ $guide['title'] }}</strong>
                            <span>{{ $guide['copy'] }}</span>
                        </a>
                    @endforeach
                </div>
            </div>
        </section>

        <section class="front-section" id="products">
            <div class="section-head">
                <div>
                    <span>New and fast moving</span>
                    <h2>Ready for home trial</h2>
                </div>
                <a href="#bag">Open bag</a>
            </div>

            <div class="product-grid">
                @forelse($products as $product)
                    <article class="product-card">
                        <div class="product-image">
                            <img src="{{ $product->main_image['url'] ?? $assetImage }}" alt="{{ $product->name }}">
                            @if($product->try_cloth_available)
                                <span>Try First</span>
                            @endif
                        </div>
                        <div class="product-body">
                            <p>{{ $product->brand ?: optional($product->category)->name ?: 'StyleOne' }}</p>
                            <h3>{{ $product->name }}</h3>
                            <div class="price-row">
                                <strong>{{ $money($product->discount_price ?: $product->price) }}</strong>
                                @if($product->discount_price)
                                    <del>{{ $money($product->price) }}</del>
                                @endif
                            </div>
                            <button type="button" class="add-btn" data-add-bag>Add</button>
                        </div>
                    </article>
                @empty
                    @foreach($fallbackProducts as $product)
                        <article class="product-card">
                            <div class="product-image">
                                <img src="{{ $assetImage }}" alt="{{ $product['name'] }}">
                                <span>{{ $product['tag'] }}</span>
                            </div>
                            <div class="product-body">
                                <p>{{ $product['brand'] }}</p>
                                <h3>{{ $product['name'] }}</h3>
                                <div class="price-row">
                                    <strong>{{ $money($product['discount_price']) }}</strong>
                                    <del>{{ $money($product['price']) }}</del>
                                </div>
                                <button type="button" class="add-btn" data-add-bag>Add</button>
                            </div>
                        </article>
                    @endforeach
                @endforelse
            </div>
        </section>

        <section class="app-download-band">
            <div>
                <span>Get the app</span>
                <h2>Faster checkout, live trial bag and delivery tracking.</h2>
                <p>Scan the QR-style block or install from your mobile browser. App links can be connected from admin settings later.</p>
            </div>
            <div class="app-actions">
                <div class="qr-box">
                    <i class="fas fa-qrcode"></i>
                    <span>STYLEONE</span>
                </div>
                <a href="#" class="store-btn"><i class="fab fa-apple"></i> App Store</a>
                <a href="#" class="store-btn"><i class="fab fa-google-play"></i> Google Play</a>
            </div>
        </section>

        <section class="zilo-full-banner">
            <a href="#products" class="zilo-image-banner">
                <img src="{{ $ziloAsset('last-minute.jpg') }}" alt="Last minute just go">
            </a>
        </section>

        <section class="trial-band">
            <div>
                <span>How it works</span>
                <h2>Doorstep trial in three simple steps</h2>
            </div>
            <div class="trial-steps">
                <article><strong>1</strong><p>Select outfits and pincode</p></article>
                <article><strong>2</strong><p>Delivery partner brings your trial bag</p></article>
                <article><strong>3</strong><p>Pay only for what you keep</p></article>
            </div>
        </section>

        <section class="front-section" id="zones">
            <div class="section-head">
                <div>
                    <span>Available zones</span>
                    <h2>Quick delivery areas</h2>
                </div>
            </div>
            <div class="zone-list">
                @forelse($zones as $zone)
                    <button type="button" data-location="{{ $zone->area ?: $zone->city }} - {{ $zone->pincode }}">
                        <i class="fas fa-location-dot"></i>
                        {{ $zone->area ?: $zone->city }} <span>{{ $zone->pincode }}</span>
                    </button>
                @empty
                    @foreach(['Bandra 400050', 'Andheri 400053', 'Powai 400076', 'Lower Parel 400013', 'Thane 400601'] as $zone)
                        <button type="button" data-location="{{ $zone }}">
                            <i class="fas fa-location-dot"></i>
                            {{ $zone }}
                        </button>
                    @endforeach
                @endforelse
            </div>
        </section>

        <section class="seo-block">
            <div>
                <span>Same-day clothing delivery</span>
                <h2>Try-first fashion for Mumbai-style urgent plans</h2>
                <p>
                    StyleOne is designed for quick-commerce clothing: discover products, choose a delivery zone,
                    get trial-ready items at home, and pay only for what you keep. Admin can manage delivery zones,
                    homepage banners, collections, products, variants, payments, returns and delivery tracking.
                </p>
            </div>
            <div class="faq-list">
                @foreach($seoFaqs as $faq)
                    <details>
                        <summary>{{ $faq['q'] }}</summary>
                        <p>{{ $faq['a'] }}</p>
                    </details>
                @endforeach
            </div>
        </section>
    </main>
</div>

<div class="toast" id="frontToast">Added to trial bag</div>
@endsection
