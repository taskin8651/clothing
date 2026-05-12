<?php

namespace App\Http\Requests;

use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpFoundation\Response;

class AssignDeliveryBoyRequest extends FormRequest
{
    public function authorize()
    {
        abort_if(Gate::denies('order_assign_delivery'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return true;
    }

    public function rules()
    {
        return [
            'delivery_boy_id' => ['required', 'exists:delivery_boys,id'],
            'note' => ['nullable', 'string'],
        ];
    }
}
