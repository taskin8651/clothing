<?php

namespace App\Models;

use DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DeliveryTracking extends Model
{
    use SoftDeletes, HasFactory;

    public const STATUSES = [
        'pending' => 'Pending',
        'assigned' => 'Assigned',
        'pickup_pending' => 'Pickup Pending',
        'picked_up' => 'Picked Up',
        'out_for_delivery' => 'Out For Delivery',
        'delivered' => 'Delivered',
        'failed_delivery' => 'Failed Delivery',
        'cancelled' => 'Cancelled',
    ];

    public $table = 'delivery_trackings';

    protected $fillable = [
        'tracking_number',
        'order_id',
        'shop_id',
        'delivery_boy_id',
        'customer_id',
        'customer_address_id',
        'pickup_address',
        'delivery_address',
        'city',
        'area',
        'pincode',
        'latitude',
        'longitude',
        'status',
        'cod_amount',
        'cod_collected',
        'cod_collected_at',
        'assigned_at',
        'pickup_pending_at',
        'picked_up_at',
        'out_for_delivery_at',
        'delivered_at',
        'failed_delivery_at',
        'cancelled_at',
        'failure_reason',
        'delivery_note',
        'admin_note',
    ];

    protected $casts = [
        'cod_amount' => 'decimal:2',
        'cod_collected' => 'boolean',
        'cod_collected_at' => 'datetime',
        'assigned_at' => 'datetime',
        'pickup_pending_at' => 'datetime',
        'picked_up_at' => 'datetime',
        'out_for_delivery_at' => 'datetime',
        'delivered_at' => 'datetime',
        'failed_delivery_at' => 'datetime',
        'cancelled_at' => 'datetime',
    ];

    protected static function booted()
    {
        static::creating(function (DeliveryTracking $deliveryTracking) {
            if (! $deliveryTracking->tracking_number) {
                $deliveryTracking->tracking_number = static::generateTrackingNumber();
            }
        });
    }

    public static function generateTrackingNumber(): string
    {
        $year = now()->format('Y');
        $prefix = 'TRK-' . $year . '-';
        $latest = static::withTrashed()
            ->where('tracking_number', 'like', $prefix . '%')
            ->orderByDesc('id')
            ->first();

        $next = $latest ? ((int) substr($latest->tracking_number, -5)) + 1 : 1;

        return $prefix . str_pad($next, 5, '0', STR_PAD_LEFT);
    }

    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id');
    }

    public function shop()
    {
        return $this->belongsTo(Shop::class, 'shop_id');
    }

    public function deliveryBoy()
    {
        return $this->belongsTo(DeliveryBoy::class, 'delivery_boy_id');
    }

    public function customer()
    {
        return $this->belongsTo(User::class, 'customer_id');
    }

    public function customerAddress()
    {
        return $this->belongsTo(UserAddress::class, 'customer_address_id');
    }

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }
}
