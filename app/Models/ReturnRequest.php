<?php

namespace App\Models;

use DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ReturnRequest extends Model
{
    use SoftDeletes, HasFactory;

    public const STATUSES = [
        'requested' => 'Requested',
        'approved' => 'Approved',
        'rejected' => 'Rejected',
        'picked_up' => 'Picked Up',
        'refunded' => 'Refunded',
        'cancelled' => 'Cancelled',
    ];

    public $table = 'return_requests';

    protected $fillable = [
        'return_number',
        'order_id',
        'order_item_id',
        'customer_id',
        'shop_id',
        'product_name',
        'size',
        'color',
        'quantity',
        'price',
        'refund_amount',
        'reason',
        'description',
        'status',
        'admin_note',
        'requested_at',
        'approved_at',
        'rejected_at',
        'picked_up_at',
        'refunded_at',
        'cancelled_at',
    ];

    protected $casts = [
        'quantity' => 'integer',
        'price' => 'decimal:2',
        'refund_amount' => 'decimal:2',
        'requested_at' => 'datetime',
        'approved_at' => 'datetime',
        'rejected_at' => 'datetime',
        'picked_up_at' => 'datetime',
        'refunded_at' => 'datetime',
        'cancelled_at' => 'datetime',
    ];

    protected static function booted()
    {
        static::creating(function (ReturnRequest $returnRequest) {
            if (! $returnRequest->return_number) {
                $returnRequest->return_number = static::generateReturnNumber();
            }
        });
    }

    public static function generateReturnNumber(): string
    {
        $year = now()->format('Y');
        $prefix = 'RET-' . $year . '-';
        $latest = static::withTrashed()->where('return_number', 'like', $prefix . '%')->orderByDesc('id')->first();
        $next = $latest ? ((int) substr($latest->return_number, -5)) + 1 : 1;

        return $prefix . str_pad($next, 5, '0', STR_PAD_LEFT);
    }

    public function order() { return $this->belongsTo(Order::class, 'order_id'); }
    public function orderItem() { return $this->belongsTo(OrderItem::class, 'order_item_id'); }
    public function customer() { return $this->belongsTo(User::class, 'customer_id'); }
    public function shop() { return $this->belongsTo(Shop::class, 'shop_id'); }

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }
}
