<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\DeliveryZone;
use App\Models\HomepageSection;
use App\Models\Product;
use App\Models\Shop;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class FrontendDemoSeeder extends Seeder
{
    private string $assetDir = 'assets/frontend/images/zilo-jpg';

    public function run()
    {
        $shop = Shop::updateOrCreate(
            ['shop_name' => 'StyleOne Mumbai Hub'],
            [
                'owner_name' => 'StyleOne Operations',
                'mobile' => '9999999999',
                'email' => 'hello@styleone.test',
                'address' => 'Bandra West, Mumbai',
                'city' => 'Mumbai',
                'area' => 'Bandra',
                'pincode' => '400050',
                'opening_time' => '07:00',
                'closing_time' => '23:00',
                'status' => 1,
            ]
        );

        $categories = $this->seedCategories();
        $this->seedProducts($shop, $categories);
        $this->seedDeliveryZones($shop);
        $this->seedHomepageSections($categories);
    }

    private function seedCategories(): array
    {
        $items = [
            ['Dresses', 'cat-dresses.jpg'],
            ['Footwear', 'cat-footwear.jpg'],
            ['Tops', 'cat-tops.jpg'],
            ['Jeans', 'cat-jeans.jpg'],
            ['Kurtas & Sets', 'cat-kurtas.jpg'],
            ['Bags', 'cat-bags.jpg'],
            ['Jewellery', 'cat-jewellery.jpg'],
            ['Bottoms', 'cat-skirts.jpg'],
            ['Activewear', 'cat-lingerie.jpg'],
            ['Innerwear', 'women-office.jpg'],
            ['Sarees', 'women-desi.jpg'],
            ['Watches', 'mall-3.jpg'],
        ];

        $categories = [];

        foreach ($items as $index => [$name, $image]) {
            $category = Category::updateOrCreate(
                ['slug' => Str::slug($name)],
                [
                    'name' => $name,
                    'parent_id' => null,
                    'status' => 1,
                    'sort_order' => $index + 1,
                ]
            );

            $categories[$name] = ['model' => $category, 'image' => $image];
        }

        return $categories;
    }

    private function seedProducts(Shop $shop, array $categories): void
    {
        $items = [
            ['Effortless Co-ord Set', 'Dresses', 'Zilo Edit', 2599, 1499, 'carousel-1.jpg'],
            ['Summer Linen Shirt', 'Tops', 'Cool For Summer', 1799, 1199, 'women-summer.jpg'],
            ['Evening Black Dress', 'Dresses', 'Plans Tonight', 2999, 1999, 'women-plans.jpg'],
            ['Tan Heel Pair', 'Footwear', 'Shoe Gallery', 2199, 1599, 'cat-footwear.jpg'],
            ['Printed Kurta Set', 'Kurtas & Sets', 'Make It Desi', 2499, 1699, 'cat-kurtas.jpg'],
            ['Everyday Sling Bag', 'Bags', 'Mall To Home', 1899, 1299, 'cat-bags.jpg'],
            ['Gold Charm Jewellery', 'Jewellery', 'A-List Picks', 1299, 799, 'cat-jewellery.jpg'],
            ['Wide Leg Bottoms', 'Bottoms', 'Summer Refresh', 1699, 999, 'summer-wide.jpg'],
            ['Active Comfort Set', 'Activewear', 'Game Ready', 1999, 1399, 'mood-game.jpg'],
        ];

        foreach ($items as [$name, $categoryName, $brand, $price, $discount, $image]) {
            $category = $categories[$categoryName]['model'] ?? null;

            $product = Product::updateOrCreate(
                ['slug' => Str::slug($name)],
                [
                    'shop_id' => $shop->id,
                    'category_id' => $category ? $category->id : null,
                    'name' => $name,
                    'sku' => strtoupper(Str::slug($name, '-')),
                    'brand' => $brand,
                    'fabric' => 'Premium blend',
                    'short_description' => 'Trial-ready style for quick delivery.',
                    'description' => 'Dummy product seeded for the Zilo-style storefront.',
                    'price' => $price,
                    'discount_price' => $discount,
                    'stock_quantity' => 25,
                    'try_cloth_available' => 1,
                    'return_available' => 1,
                    'is_featured' => 1,
                    'status' => 1,
                ]
            );

            $this->attachProductImage($product, $image);
        }
    }

    private function seedDeliveryZones(Shop $shop): void
    {
        foreach ([
            ['Bandra', '400050'],
            ['Andheri', '400053'],
            ['Powai', '400076'],
            ['Lower Parel', '400013'],
            ['Thane', '400601'],
        ] as $index => [$area, $pincode]) {
            DeliveryZone::updateOrCreate(
                ['shop_id' => $shop->id, 'pincode' => $pincode],
                [
                    'city' => 'Mumbai',
                    'area' => $area,
                    'min_delivery_minutes' => 60,
                    'max_delivery_minutes' => 120,
                    'delivery_charge' => 49,
                    'free_delivery_min_amount' => 1299,
                    'try_first_enabled' => 1,
                    'trial_wait_minutes' => 30,
                    'cod_enabled' => 1,
                    'status' => 1,
                    'sort_order' => $index + 1,
                ]
            );
        }
    }

    private function seedHomepageSections(array $categories): void
    {
        $sections = [
            ['carousel', 'Effortless ease: unified co-ords.', 'Up to 70% off', 'carousel-1.jpg'],
            ['carousel', 'Summer styles, delivered fast.', 'Try first buy later', 'carousel-2.jpg'],
            ['carousel', 'Plans tonight? We got you.', '60 min delivery', 'carousel-3.jpg'],
            ['wear_edit', 'MAKE IT DESI', null, 'women-desi.jpg'],
            ['wear_edit', 'COOL FOR SUMMER', null, 'women-summer.jpg'],
            ['wear_edit', 'PLANS TONIGHT?', null, 'women-plans.jpg'],
            ['wear_edit', 'OUTFIT FILLER', null, 'women-office.jpg'],
            ['mood', 'GAME READY', null, 'mood-game.jpg'],
            ['mood', 'HOLIDAY DRIP', null, 'mood-holiday.jpg'],
            ['mood', 'URBAN EASE', null, 'mood-urban.jpg'],
            ['mood', 'NIGHT FEVER', null, 'mood-night.jpg'],
            ['coupon', 'First Order Code APP50', 'Extra 50% off', 'coupon.jpg'],
            ['coupon', 'Code DRESS15', 'Extra 15% off', 'coupon.jpg'],
            ['coupon', 'Cart Offer', 'Extra 100 off', 'coupon.jpg'],
            ['summer_pick', 'Strappy Styles', null, 'summer-strap.jpg'],
            ['summer_pick', 'Denim Shorts', null, 'summer-denim.jpg'],
            ['summer_pick', 'Wide Leg Fits', null, 'summer-wide.jpg'],
            ['brand_card', 'Bata', null, 'brand-1.jpg'],
            ['brand_card', 'Palmonas', null, 'brand-2.jpg'],
            ['brand_card', 'Puma', null, 'brand-3.jpg'],
            ['sports_card', 'Bengaluru', null, 'sports-1.jpg'],
            ['sports_card', 'Delhi', null, 'sports-2.jpg'],
            ['sports_card', 'Chennai', null, 'sports-3.jpg'],
            ['store_card', 'The Last Minute Store', null, 'store-last.jpg'],
            ['store_card', 'The Shoe Gallery', null, 'store-shoe.jpg'],
            ['alist_pick', 'Bata', null, 'alist-1.jpg'],
            ['alist_pick', 'Palmonas', null, 'alist-2.jpg'],
            ['alist_pick', 'Puma', null, 'alist-3.jpg'],
            ['director', 'CURATED BY OUR STYLE DIRECTOR', null, 'director.jpg'],
            ['collection', 'The Summer Travel Store', null, 'travel.jpg'],
            ['collection', 'Style Director Store', null, 'style-director.jpg'],
            ['collection', 'The Shoe Gallery Store', null, 'shoe-gallery.jpg'],
            ['banner', 'Cannes Reimagined', 'Celebrity-inspired looks', 'cannes.jpg'],
            ['mall_pick', 'Inc.5', null, 'mall-1.jpg'],
            ['mall_pick', 'Pepe Jeans', null, 'mall-2.jpg'],
            ['mall_pick', 'JUST IN TIME', null, 'mall-3.jpg'],
            ['collection', 'The Last Minute Store', null, 'last-store.jpg'],
        ];

        foreach ($sections as $index => [$type, $title, $subtitle, $image]) {
            HomepageSection::updateOrCreate(
                ['type' => $type, 'title' => $title],
                [
                    'subtitle' => $subtitle,
                    'audience' => 'women',
                    'placement' => 'home',
                    'image' => $this->storePublicImage($image),
                    'link_url' => '#products',
                    'cta_text' => 'Shop Now',
                    'category_id' => $categories['Dresses']['model']->id ?? null,
                    'status' => 1,
                    'sort_order' => $index + 1,
                ]
            );
        }
    }

    private function attachProductImage(Product $product, string $image): void
    {
        if ($product->getFirstMedia('main_image')) {
            return;
        }

        $path = public_path($this->assetDir . '/' . $image);

        if (file_exists($path)) {
            $product
                ->addMedia($path)
                ->preservingOriginal()
                ->toMediaCollection('main_image');
        }
    }

    private function storePublicImage(string $image): ?string
    {
        $source = public_path($this->assetDir . '/' . $image);

        if (! file_exists($source)) {
            return null;
        }

        $target = 'homepage-sections/' . $image;

        if (! Storage::disk('public')->exists($target)) {
            Storage::disk('public')->put($target, file_get_contents($source));
        }

        return $target;
    }
}
