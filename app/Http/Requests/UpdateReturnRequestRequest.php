<?php

namespace App\Http\Requests;

use App\Models\ReturnRequest;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Symfony\Component\HttpFoundation\Response;

class UpdateReturnRequestRequest extends FormRequest
{
    public function authorize()
    {
        abort_if(Gate::denies('return_request_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        return true;
    }

    public function rules()
    {
        return [
            'order_id' => ['required', 'exists:orders,id'],
            'order_item_id' => ['required', 'exists:order_items,id'],
            'customer_id' => ['nullable', 'exists:users,id'],
            'shop_id' => ['nullable', 'exists:shops,id'],
            'refund_amount' => ['nullable', 'numeric', 'min:0'],
            'reason' => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'status' => ['nullable', Rule::in(array_keys(ReturnRequest::STATUSES))],
            'admin_note' => ['nullable', 'string'],
        ];
    }
}
