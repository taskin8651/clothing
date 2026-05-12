<?php

namespace App\Http\Requests;

use App\Models\Invoice;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Symfony\Component\HttpFoundation\Response;

class StoreInvoiceRequest extends FormRequest
{
    public function authorize()
    {
        abort_if(Gate::denies('invoice_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        return true;
    }

    public function rules()
    {
        return [
            'order_id' => ['nullable', 'exists:orders,id'],
            'customer_id' => ['nullable', 'exists:users,id'],
            'shop_id' => ['nullable', 'exists:shops,id'],
            'payment_id' => ['nullable', 'exists:payments,id'],
            'invoice_date' => ['nullable', 'date'],
            'due_date' => ['nullable', 'date'],
            'customer_name' => ['nullable', 'string', 'max:255'],
            'customer_mobile' => ['nullable', 'string', 'max:30'],
            'customer_email' => ['nullable', 'email', 'max:255'],
            'billing_address' => ['nullable', 'string'],
            'shipping_address' => ['nullable', 'string'],
            'shop_name' => ['nullable', 'string', 'max:255'],
            'shop_mobile' => ['nullable', 'string', 'max:30'],
            'shop_email' => ['nullable', 'email', 'max:255'],
            'shop_address' => ['nullable', 'string'],
            'shop_gst_number' => ['nullable', 'string', 'max:100'],
            'subtotal' => ['nullable', 'numeric', 'min:0'],
            'discount_amount' => ['nullable', 'numeric', 'min:0'],
            'tax_amount' => ['nullable', 'numeric', 'min:0'],
            'delivery_charge' => ['nullable', 'numeric', 'min:0'],
            'round_off' => ['nullable', 'numeric'],
            'total_amount' => ['required', 'numeric', 'min:0'],
            'paid_amount' => ['nullable', 'numeric', 'min:0'],
            'payment_method' => ['nullable', 'string', 'max:50'],
            'payment_status' => ['nullable', 'string', 'max:50'],
            'invoice_status' => ['nullable', Rule::in(array_keys(Invoice::STATUSES))],
            'notes' => ['nullable', 'string'],
            'terms' => ['nullable', 'string'],
            'admin_note' => ['nullable', 'string'],
            'items' => ['nullable', 'array'],
            'items.*.order_item_id' => ['nullable', 'exists:order_items,id'],
            'items.*.product_id' => ['nullable', 'exists:products,id'],
            'items.*.product_variant_id' => ['nullable', 'exists:product_variants,id'],
            'items.*.product_name' => ['nullable', 'string', 'max:255'],
            'items.*.product_sku' => ['nullable', 'string', 'max:255'],
            'items.*.size' => ['nullable', 'string', 'max:255'],
            'items.*.color' => ['nullable', 'string', 'max:255'],
            'items.*.price' => ['nullable', 'numeric', 'min:0'],
            'items.*.quantity' => ['nullable', 'integer', 'min:1'],
            'items.*.tax_amount' => ['nullable', 'numeric', 'min:0'],
            'items.*.discount_amount' => ['nullable', 'numeric', 'min:0'],
            'items.*.total' => ['nullable', 'numeric', 'min:0'],
        ];
    }
}
