<?php

namespace App\Models;

use DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Shop extends Model
{
    use SoftDeletes, HasFactory;

    public $table = 'shops';

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'shop_name',
        'owner_name',
        'mobile',
        'email',
        'address',
        'city',
        'area',
        'pincode',
        'latitude',
        'longitude',
        'opening_time',
        'closing_time',
        'status',
        'sort_order',
    ];

    public function products()
    {
        return $this->hasMany(Product::class, 'shop_id');
    }

    public function deliveryTrackings()
    {
        return $this->hasMany(DeliveryTracking::class, 'shop_id');
    }

    public function invoices()
    {
        return $this->hasMany(Invoice::class, 'shop_id');
    }

    public function receipts()
    {
        return $this->hasMany(Receipt::class, 'shop_id');
    }

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }
}
