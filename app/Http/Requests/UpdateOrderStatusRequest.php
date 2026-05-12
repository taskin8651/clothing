<?php

namespace App\Http\Requests;

use App\Models\Order;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Symfony\Component\HttpFoundation\Response;

class UpdateOrderStatusRequest extends FormRequest
{
    public function authorize()
    {
        abort_if(Gate::denies('order_status_update'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return true;
    }

    public function rules()
    {
        return [
            'order_status' => ['required', Rule::in(array_keys(Order::ORDER_STATUSES))],
            'note' => ['nullable', 'string'],
        ];
    }
}
