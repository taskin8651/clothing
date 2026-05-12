<?php

namespace App\Http\Requests;

use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpFoundation\Response;

class UpdateCustomerAddressRequest extends FormRequest
{
    public function authorize()
    {
        abort_if(Gate::denies('customer_address_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return true;
    }

    public function rules()
    {
        return [
            'customer_id' => [
                'required',
                'exists:users,id',
            ],
            'user_id' => [
                'nullable',
                'exists:users,id',
            ],
            'name' => [
                'nullable',
                'string',
                'max:255',
            ],
            'mobile' => [
                'nullable',
                'string',
                'max:20',
            ],
            'address' => [
                'required',
                'string',
            ],
            'landmark' => [
                'nullable',
                'string',
                'max:255',
            ],
            'city' => [
                'nullable',
                'string',
                'max:255',
            ],
            'area' => [
                'nullable',
                'string',
                'max:255',
            ],
            'pincode' => [
                'nullable',
                'string',
                'max:20',
            ],
            'latitude' => [
                'nullable',
                'numeric',
            ],
            'longitude' => [
                'nullable',
                'numeric',
            ],
            'is_default' => [
                'nullable',
                'boolean',
            ],
            'status' => [
                'nullable',
                'boolean',
            ],
        ];
    }
}
