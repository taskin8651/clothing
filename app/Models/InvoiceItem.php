<?php

namespace App\Models;

use DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InvoiceItem extends Model
{
    use HasFactory;

    public $table = 'invoice_items';

    protected $fillable = [
        'invoice_id', 'order_item_id', 'product_id', 'product_variant_id', 'product_name', 'product_sku',
        'size', 'color', 'price', 'quantity', 'tax_amount', 'discount_amount', 'total',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'quantity' => 'integer',
        'tax_amount' => 'decimal:2',
        'discount_amount' => 'decimal:2',
        'total' => 'decimal:2',
    ];

    public function invoice() { return $this->belongsTo(Invoice::class, 'invoice_id'); }
    public function orderItem() { return $this->belongsTo(OrderItem::class, 'order_item_id'); }
    public function product() { return $this->belongsTo(Product::class, 'product_id'); }
    public function productVariant() { return $this->belongsTo(ProductVariant::class, 'product_variant_id'); }

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }
}
