<?php

namespace App\Http\Requests;

use App\Models\DeliveryTracking;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Symfony\Component\HttpFoundation\Response;

class UpdateDeliveryTrackingRequest extends FormRequest
{
    public function authorize()
    {
        abort_if(Gate::denies('delivery_tracking_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return true;
    }

    public function rules()
    {
        return [
            'order_id' => ['nullable', 'exists:orders,id'],
            'shop_id' => ['nullable', 'exists:shops,id'],
            'delivery_boy_id' => ['nullable', 'exists:delivery_boys,id'],
            'customer_id' => ['nullable', 'exists:users,id'],
            'customer_address_id' => ['nullable', 'exists:user_addresses,id'],
            'pickup_address' => ['nullable', 'string'],
            'delivery_address' => ['nullable', 'string'],
            'city' => ['nullable', 'string', 'max:255'],
            'area' => ['nullable', 'string', 'max:255'],
            'pincode' => ['nullable', 'string', 'max:20'],
            'latitude' => ['nullable', 'numeric'],
            'longitude' => ['nullable', 'numeric'],
            'status' => ['nullable', Rule::in(array_keys(DeliveryTracking::STATUSES))],
            'cod_amount' => ['nullable', 'numeric', 'min:0'],
            'cod_collected' => ['nullable', 'boolean'],
            'failure_reason' => ['nullable', 'string', 'max:255'],
            'delivery_note' => ['nullable', 'string'],
            'admin_note' => ['nullable', 'string'],
        ];
    }
}
