<?php

namespace App\Models;

use DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DeliveryZone extends Model
{
    use SoftDeletes, HasFactory;

    public $table = 'delivery_zones';

    protected $fillable = [
        'shop_id',
        'city',
        'area',
        'pincode',
        'min_delivery_minutes',
        'max_delivery_minutes',
        'delivery_charge',
        'free_delivery_min_amount',
        'try_first_enabled',
        'trial_wait_minutes',
        'cod_enabled',
        'status',
        'sort_order',
    ];

    protected $casts = [
        'delivery_charge' => 'decimal:2',
        'free_delivery_min_amount' => 'decimal:2',
        'try_first_enabled' => 'boolean',
        'cod_enabled' => 'boolean',
        'status' => 'boolean',
    ];

    public function shop()
    {
        return $this->belongsTo(Shop::class, 'shop_id');
    }

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }
}
