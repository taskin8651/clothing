<?php

namespace App\Models;

use DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Invoice extends Model
{
    use SoftDeletes, HasFactory;

    public const STATUSES = [
        'draft' => 'Draft',
        'issued' => 'Issued',
        'paid' => 'Paid',
        'cancelled' => 'Cancelled',
        'refunded' => 'Refunded',
    ];

    public $table = 'invoices';

    protected $fillable = [
        'invoice_number', 'order_id', 'customer_id', 'shop_id', 'payment_id', 'invoice_date', 'due_date',
        'customer_name', 'customer_mobile', 'customer_email', 'billing_address', 'shipping_address',
        'shop_name', 'shop_mobile', 'shop_email', 'shop_address', 'shop_gst_number',
        'subtotal', 'discount_amount', 'tax_amount', 'delivery_charge', 'round_off',
        'total_amount', 'paid_amount', 'due_amount', 'payment_method', 'payment_status',
        'invoice_status', 'notes', 'terms', 'admin_note',
    ];

    protected $casts = [
        'invoice_date' => 'date',
        'due_date' => 'date',
        'subtotal' => 'decimal:2',
        'discount_amount' => 'decimal:2',
        'tax_amount' => 'decimal:2',
        'delivery_charge' => 'decimal:2',
        'round_off' => 'decimal:2',
        'total_amount' => 'decimal:2',
        'paid_amount' => 'decimal:2',
        'due_amount' => 'decimal:2',
    ];

    protected static function booted()
    {
        static::creating(function (Invoice $invoice) {
            if (! $invoice->invoice_number) {
                $invoice->invoice_number = static::generateInvoiceNumber();
            }
        });
    }

    public static function generateInvoiceNumber(): string
    {
        $year = now()->format('Y');
        $prefix = 'INV-' . $year . '-';
        $latest = static::withTrashed()->where('invoice_number', 'like', $prefix . '%')->orderByDesc('id')->first();
        $next = $latest ? ((int) substr($latest->invoice_number, -5)) + 1 : 1;

        return $prefix . str_pad($next, 5, '0', STR_PAD_LEFT);
    }

    public function order() { return $this->belongsTo(Order::class, 'order_id'); }
    public function customer() { return $this->belongsTo(User::class, 'customer_id'); }
    public function shop() { return $this->belongsTo(Shop::class, 'shop_id'); }
    public function payment() { return $this->belongsTo(Payment::class, 'payment_id'); }
    public function items() { return $this->hasMany(InvoiceItem::class, 'invoice_id'); }
    public function receipts() { return $this->hasMany(Receipt::class, 'invoice_id'); }

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }
}
