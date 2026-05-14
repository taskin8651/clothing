<?php

namespace App\Models;

use DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class HomepageSection extends Model
{
    use SoftDeletes, HasFactory;

    public const TYPES = [
        'hero' => 'Hero Banner',
        'banner' => 'Banner',
        'category_strip' => 'Category Strip',
        'collection' => 'Collection',
        'brand_highlight' => 'Brand Highlight',
        'offer' => 'Offer',
        'carousel' => 'Carousel',
        'wear_edit' => 'Wear Edit',
        'mood' => 'Mood',
        'coupon' => 'Coupon',
        'summer_pick' => 'Summer Pick',
        'brand_card' => 'Brand Card',
        'sports_card' => 'Sports Card',
        'store_card' => 'Store Card',
        'alist_pick' => 'A-List Pick',
        'director' => 'Style Director',
        'mall_pick' => 'Mall Pick',
    ];

    public const AUDIENCES = [
        'all' => 'All',
        'women' => 'Women',
        'men' => 'Men',
        'kids' => 'Kids',
    ];

    public $table = 'homepage_sections';

    protected $fillable = [
        'title',
        'subtitle',
        'type',
        'audience',
        'placement',
        'image',
        'link_url',
        'cta_text',
        'category_id',
        'product_id',
        'starts_at',
        'ends_at',
        'status',
        'sort_order',
    ];

    protected $casts = [
        'starts_at' => 'datetime',
        'ends_at' => 'datetime',
        'status' => 'boolean',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    public function getImageUrlAttribute()
    {
        return $this->image ? asset('storage/' . $this->image) : null;
    }

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }
}
