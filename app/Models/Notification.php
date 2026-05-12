<?php

namespace App\Models;

use DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Notification extends Model
{
    use SoftDeletes, HasFactory;

    public $table = 'notifications';

    protected $fillable = [
        'title',
        'message',
        'type',
        'icon',
        'color',
        'notifiable_id',
        'notifiable_type',
        'related_id',
        'related_type',
        'action_url',
        'data',
        'is_read',
        'read_at',
    ];

    protected $casts = [
        'data' => 'array',
        'is_read' => 'boolean',
        'read_at' => 'datetime',
    ];

    public const TYPE_ORDER = 'order';
    public const TYPE_PAYMENT = 'payment';
    public const TYPE_DELIVERY = 'delivery';
    public const TYPE_RETURN = 'return';
    public const TYPE_INVOICE = 'invoice';
    public const TYPE_STOCK = 'stock';
    public const TYPE_SYSTEM = 'system';

    public static function types()
    {
        return [
            self::TYPE_ORDER => 'Order',
            self::TYPE_PAYMENT => 'Payment',
            self::TYPE_DELIVERY => 'Delivery',
            self::TYPE_RETURN => 'Return',
            self::TYPE_INVOICE => 'Invoice',
            self::TYPE_STOCK => 'Stock',
            self::TYPE_SYSTEM => 'System',
        ];
    }

    public function notifiable()
    {
        return $this->morphTo();
    }

    public function related()
    {
        return $this->morphTo();
    }

    public function scopeUnread($query)
    {
        return $query->where('is_read', 0);
    }

    public function scopeRead($query)
    {
        return $query->where('is_read', 1);
    }

    public function markAsRead()
    {
        $this->update([
            'is_read' => 1,
            'read_at' => now(),
        ]);
    }

    public static function createAdminNotification(array $data)
    {
        return self::create([
            'title' => $data['title'] ?? null,
            'message' => $data['message'] ?? null,
            'type' => $data['type'] ?? self::TYPE_SYSTEM,
            'icon' => $data['icon'] ?? 'fas fa-bell',
            'color' => $data['color'] ?? '#4F46E5',
            'notifiable_id' => $data['notifiable_id'] ?? null,
            'notifiable_type' => $data['notifiable_type'] ?? null,
            'related_id' => $data['related_id'] ?? null,
            'related_type' => $data['related_type'] ?? null,
            'action_url' => $data['action_url'] ?? null,
            'data' => $data['data'] ?? null,
            'is_read' => 0,
        ]);
    }

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }
}