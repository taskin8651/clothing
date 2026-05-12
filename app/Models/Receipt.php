<?php

namespace App\Models;

use DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Receipt extends Model
{
    use SoftDeletes, HasFactory;

    public const TYPES = ['payment' => 'Payment', 'cod' => 'COD', 'refund' => 'Refund'];
    public const STATUSES = ['pending' => 'Pending', 'paid' => 'Paid', 'failed' => 'Failed', 'refunded' => 'Refunded', 'cancelled' => 'Cancelled'];

    public $table = 'receipts';

    protected $fillable = [
        'receipt_number', 'invoice_id', 'order_id', 'payment_id', 'customer_id', 'shop_id',
        'receipt_type', 'receipt_date', 'payment_method', 'payment_gateway', 'transaction_id',
        'amount', 'status', 'received_from', 'received_by', 'notes', 'admin_note',
    ];

    protected $casts = [
        'receipt_date' => 'date',
        'amount' => 'decimal:2',
    ];

    protected static function booted()
    {
        static::creating(function (Receipt $receipt) {
            if (! $receipt->receipt_number) {
                $receipt->receipt_number = static::generateReceiptNumber();
            }
        });
    }

    public static function generateReceiptNumber(): string
    {
        $year = now()->format('Y');
        $prefix = 'RCP-' . $year . '-';
        $latest = static::withTrashed()->where('receipt_number', 'like', $prefix . '%')->orderByDesc('id')->first();
        $next = $latest ? ((int) substr($latest->receipt_number, -5)) + 1 : 1;

        return $prefix . str_pad($next, 5, '0', STR_PAD_LEFT);
    }

    public function invoice() { return $this->belongsTo(Invoice::class, 'invoice_id'); }
    public function order() { return $this->belongsTo(Order::class, 'order_id'); }
    public function payment() { return $this->belongsTo(Payment::class, 'payment_id'); }
    public function customer() { return $this->belongsTo(User::class, 'customer_id'); }
    public function shop() { return $this->belongsTo(Shop::class, 'shop_id'); }

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }
}
