<?php

namespace App\Http\Requests;

use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpFoundation\Response;

class StoreCustomerRequest extends FormRequest
{
    public function authorize()
    {
        abort_if(Gate::denies('customer_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return true;
    }

    public function rules()
    {
        return [
            'name' => [
                'required',
                'string',
                'max:255',
            ],

            'email' => [
                'nullable',
                'email',
                'max:255',
                'unique:users,email',
            ],

            'mobile' => [
                'nullable',
                'string',
                'max:20',
                'unique:users,mobile',
            ],

            'password' => [
                'nullable',
                'string',
                'min:6',
            ],

            'status' => [
                'nullable',
                'boolean',
            ],
        ];
    }
}
