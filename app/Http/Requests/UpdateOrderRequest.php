<?php

namespace App\Http\Requests;

use App\Models\Order;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Symfony\Component\HttpFoundation\Response;

class UpdateOrderRequest extends FormRequest
{
    public function authorize()
    {
        abort_if(Gate::denies('order_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return true;
    }

    public function rules()
    {
        return [
            'customer_id' => ['nullable', 'exists:users,id'],
            'customer_address_id' => ['nullable', 'exists:user_addresses,id'],
            'shop_id' => ['nullable', 'exists:shops,id'],
            'delivery_boy_id' => ['nullable', 'exists:delivery_boys,id'],
            'payment_method' => ['nullable', Rule::in(array_keys(Order::PAYMENT_METHODS))],
            'payment_status' => ['nullable', Rule::in(array_keys(Order::PAYMENT_STATUSES))],
            'order_status' => ['nullable', Rule::in(array_keys(Order::ORDER_STATUSES))],
            'notes' => ['nullable', 'string'],
            'admin_note' => ['nullable', 'string'],
        ];
    }
}
