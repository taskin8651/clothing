<?php

namespace App\Models;

use DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SystemSetting extends Model
{
    use HasFactory;

    public $table = 'system_settings';

    protected $fillable = [
        'company_name',
        'site_title',
        'support_email',
        'support_phone',
        'whatsapp_number',
        'gst_number',

        'address',
        'city',
        'state',
        'country',
        'pincode',

        'site_logo',
        'site_favicon',
        'invoice_logo',

        'invoice_prefix',
        'receipt_prefix',
        'order_prefix',
        'return_prefix',
        'tracking_prefix',

        'invoice_terms',
        'invoice_footer_note',

        'default_tax_percent',
        'default_delivery_charge',
        'free_delivery_min_amount',
        'return_window_days',

        'allow_return_if_try_cloth',
        'cod_enabled',
        'online_payment_enabled',

        'payment_gateway_name',
        'payment_gateway_key',
        'payment_gateway_secret',

        'facebook_url',
        'instagram_url',
        'youtube_url',
        'linkedin_url',

        'default_meta_title',
        'default_meta_description',
    ];

    protected $casts = [
        'default_tax_percent' => 'decimal:2',
        'default_delivery_charge' => 'decimal:2',
        'free_delivery_min_amount' => 'decimal:2',
        'return_window_days' => 'integer',

        'allow_return_if_try_cloth' => 'boolean',
        'cod_enabled' => 'boolean',
        'online_payment_enabled' => 'boolean',
    ];

    public static function current()
    {
        return self::firstOrCreate(['id' => 1]);
    }

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }
}