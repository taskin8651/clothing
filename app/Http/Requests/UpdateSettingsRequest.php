<?php

namespace App\Http\Requests;

use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpFoundation\Response;

class UpdateSettingsRequest extends FormRequest
{
    public function authorize()
    {
        abort_if(Gate::denies('setting_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return true;
    }

    public function rules()
    {
        return [
            'company_name' => ['nullable', 'string', 'max:255'],
            'site_title' => ['nullable', 'string', 'max:255'],
            'support_email' => ['nullable', 'email', 'max:255'],
            'support_phone' => ['nullable', 'string', 'max:30'],
            'whatsapp_number' => ['nullable', 'string', 'max:30'],
            'gst_number' => ['nullable', 'string', 'max:100'],

            'address' => ['nullable', 'string'],
            'city' => ['nullable', 'string', 'max:255'],
            'state' => ['nullable', 'string', 'max:255'],
            'country' => ['nullable', 'string', 'max:255'],
            'pincode' => ['nullable', 'string', 'max:20'],

            'invoice_prefix' => ['nullable', 'string', 'max:50'],
            'receipt_prefix' => ['nullable', 'string', 'max:50'],
            'order_prefix' => ['nullable', 'string', 'max:50'],
            'return_prefix' => ['nullable', 'string', 'max:50'],
            'tracking_prefix' => ['nullable', 'string', 'max:50'],

            'invoice_terms' => ['nullable', 'string'],
            'invoice_footer_note' => ['nullable', 'string'],

            'default_tax_percent' => ['nullable', 'numeric', 'min:0'],
            'default_delivery_charge' => ['nullable', 'numeric', 'min:0'],
            'free_delivery_min_amount' => ['nullable', 'numeric', 'min:0'],
            'return_window_days' => ['nullable', 'integer', 'min:0'],

            'allow_return_if_try_cloth' => ['nullable', 'boolean'],
            'cod_enabled' => ['nullable', 'boolean'],
            'online_payment_enabled' => ['nullable', 'boolean'],

            'payment_gateway_name' => ['nullable', 'string', 'max:255'],
            'payment_gateway_key' => ['nullable', 'string', 'max:255'],
            'payment_gateway_secret' => ['nullable', 'string', 'max:255'],

            'facebook_url' => ['nullable', 'url', 'max:255'],
            'instagram_url' => ['nullable', 'url', 'max:255'],
            'youtube_url' => ['nullable', 'url', 'max:255'],
            'linkedin_url' => ['nullable', 'url', 'max:255'],

            'default_meta_title' => ['nullable', 'string', 'max:255'],
            'default_meta_description' => ['nullable', 'string'],

            'site_logo' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp,svg', 'max:4096'],
            'site_favicon' => ['nullable', 'mimes:jpg,jpeg,png,webp,ico', 'max:2048'],
            'invoice_logo' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp,svg', 'max:4096'],

            'remove_site_logo' => ['nullable', 'boolean'],
            'remove_site_favicon' => ['nullable', 'boolean'],
            'remove_invoice_logo' => ['nullable', 'boolean'],
        ];
    }
}