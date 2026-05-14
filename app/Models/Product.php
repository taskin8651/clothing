<?php

namespace App\Models;

use DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Product extends Model implements HasMedia
{
    use SoftDeletes, HasFactory, InteractsWithMedia;

    public $table = 'products';

    protected $appends = [
        'main_image',
        'gallery_images',
    ];

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'shop_id',
        'category_id',
        'name',
        'slug',
        'sku',
        'short_description',
        'description',
        'brand',
        'fabric',
        'price',
        'discount_price',
        'stock_quantity',
        'try_cloth_available',
        'return_available',
        'is_featured',
        'status',
        'sort_order',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'discount_price' => 'decimal:2',
        'try_cloth_available' => 'boolean',
        'return_available' => 'boolean',
        'is_featured' => 'boolean',
        'status' => 'boolean',
    ];

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('main_image')->singleFile();

        $this->addMediaCollection('gallery_images');
    }

    public function getMainImageAttribute()
    {
        $file = $this->getFirstMedia('main_image');

        if ($file) {
            return [
                'id' => $file->id,
                'url' => parse_url($file->getUrl(), PHP_URL_PATH) ?: $file->getUrl(),
                'name' => $file->file_name,
            ];
        }

        return null;
    }

    public function getGalleryImagesAttribute()
    {
        return $this->getMedia('gallery_images')->map(function ($file) {
            return [
                'id' => $file->id,
                'url' => parse_url($file->getUrl(), PHP_URL_PATH) ?: $file->getUrl(),
                'name' => $file->file_name,
            ];
        });
    }

    public function shop()
    {
        return $this->belongsTo(Shop::class, 'shop_id');
    }

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }
    public function variants()
{
    return $this->hasMany(ProductVariant::class, 'product_id');
}
}
