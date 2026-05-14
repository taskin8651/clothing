<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\DeliveryZone;
use App\Models\HomepageSection;
use App\Models\Product;
use Illuminate\Support\Facades\Schema;

class HomeController extends Controller
{
    public function index()
    {
        $categories = $this->categoryCollection();
        $products = $this->productCollection();
        $sections = $this->homepageSections();
        $zones = $this->deliveryZones();

        return view('frontend.index', compact('categories', 'products', 'sections', 'zones'));
    }

    private function categoryCollection()
    {
        if (! Schema::hasTable('categories')) {
            return collect();
        }

        return Category::where('status', 1)
            ->whereNull('parent_id')
            ->orderBy('sort_order')
            ->orderBy('name')
            ->limit(18)
            ->get();
    }

    private function productCollection()
    {
        if (! Schema::hasTable('products')) {
            return collect();
        }

        return Product::with(['category', 'media'])
            ->where('status', 1)
            ->orderByDesc('is_featured')
            ->orderBy('sort_order')
            ->latest()
            ->limit(120)
            ->get();
    }

    private function homepageSections()
    {
        if (! Schema::hasTable('homepage_sections')) {
            return collect();
        }

        return HomepageSection::with(['category', 'product'])
            ->where('status', 1)
            ->where(function ($query) {
                $query->whereNull('starts_at')->orWhere('starts_at', '<=', now());
            })
            ->where(function ($query) {
                $query->whereNull('ends_at')->orWhere('ends_at', '>=', now());
            })
            ->orderBy('sort_order')
            ->latest()
            ->limit(12)
            ->get();
    }

    private function deliveryZones()
    {
        if (! Schema::hasTable('delivery_zones')) {
            return collect();
        }

        return DeliveryZone::where('status', 1)
            ->orderBy('sort_order')
            ->limit(8)
            ->get();
    }
}
