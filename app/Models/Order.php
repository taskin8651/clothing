<?php

namespace App\Models;

use DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model
{
    use SoftDeletes, HasFactory;

    public const PAYMENT_METHODS = ['online' => 'Online', 'cod' => 'COD'];

    public const PAYMENT_STATUSES = [
        'pending' => 'Pending',
        'paid' => 'Paid',
        'failed' => 'Failed',
        'refunded' => 'Refunded',
    ];

    public const ORDER_STATUSES = [
        'pending' => 'Pending',
        'confirmed' => 'Confirmed',
        'packed' => 'Packed',
        'assigned' => 'Assigned',
        'picked_up' => 'Picked Up',
        'out_for_delivery' => 'Out For Delivery',
        'delivered' => 'Delivered',
        'cancelled' => 'Cancelled',
        'returned' => 'Returned',
    ];

    public $table = 'orders';

    protected $fillable = [
        'order_number',
        'customer_id',
        'customer_address_id',
        'shop_id',
        'delivery_boy_id',
        'subtotal',
        'discount_amount',
        'delivery_charge',
        'tax_amount',
        'total_amount',
        'payment_method',
        'payment_status',
        'order_status',
        'try_cloth_selected',
        'return_eligible',
        'customer_name',
        'customer_mobile',
        'delivery_address',
        'city',
        'area',
        'pincode',
        'latitude',
        'longitude',
        'notes',
        'admin_note',
        'placed_at',
        'confirmed_at',
        'packed_at',
        'assigned_at',
        'picked_up_at',
        'out_for_delivery_at',
        'delivered_at',
        'cancelled_at',
        'returned_at',
    ];

    protected $casts = [
        'subtotal' => 'decimal:2',
        'discount_amount' => 'decimal:2',
        'delivery_charge' => 'decimal:2',
        'tax_amount' => 'decimal:2',
        'total_amount' => 'decimal:2',
        'try_cloth_selected' => 'boolean',
        'return_eligible' => 'boolean',
        'placed_at' => 'datetime',
        'confirmed_at' => 'datetime',
        'packed_at' => 'datetime',
        'assigned_at' => 'datetime',
        'picked_up_at' => 'datetime',
        'out_for_delivery_at' => 'datetime',
        'delivered_at' => 'datetime',
        'cancelled_at' => 'datetime',
        'returned_at' => 'datetime',
    ];

    protected static function booted()
    {
        static::creating(function (Order $order) {
            if (! $order->order_number) {
                $order->order_number = static::generateOrderNumber();
            }
        });
    }

    public static function generateOrderNumber(): string
    {
        $year = now()->format('Y');
        $prefix = 'ORD-' . $year . '-';
        $latest = static::withTrashed()
            ->where('order_number', 'like', $prefix . '%')
            ->orderByDesc('id')
            ->first();

        $next = $latest ? ((int) substr($latest->order_number, -5)) + 1 : 1;

        return $prefix . str_pad($next, 5, '0', STR_PAD_LEFT);
    }

    public function customer()
    {
        return $this->belongsTo(User::class, 'customer_id');
    }

    public function customerAddress()
    {
        return $this->belongsTo(UserAddress::class, 'customer_address_id');
    }

    public function shop()
    {
        return $this->belongsTo(Shop::class, 'shop_id');
    }

    public function deliveryBoy()
    {
        return $this->belongsTo(DeliveryBoy::class, 'delivery_boy_id');
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class, 'order_id');
    }

    public function statusHistories()
    {
        return $this->hasMany(OrderStatusHistory::class, 'order_id')->latest();
    }

    public function payments()
    {
        return $this->hasMany(Payment::class, 'order_id');
    }

    public function latestPayment()
    {
        return $this->hasOne(Payment::class, 'order_id')->latestOfMany();
    }

    public function invoices()
    {
        return $this->hasMany(Invoice::class, 'order_id');
    }

    public function latestInvoice()
    {
        return $this->hasOne(Invoice::class, 'order_id')->latestOfMany();
    }

    public function receipts()
    {
        return $this->hasMany(Receipt::class, 'order_id');
    }

    public function returnRequests()
    {
        return $this->hasMany(ReturnRequest::class, 'order_id');
    }

    public function deliveryTracking()
    {
        return $this->hasOne(DeliveryTracking::class, 'order_id');
    }

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }
}
