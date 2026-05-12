<?php

namespace App\Http\Requests;

use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Symfony\Component\HttpFoundation\Response;

class UpdateCustomerRequest extends FormRequest
{
    public function authorize()
    {
        abort_if(Gate::denies('customer_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return true;
    }

    public function rules()
    {
        $customerId = $this->route('customer') ? $this->route('customer')->id : null;

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
                Rule::unique('users', 'email')->ignore($customerId),
            ],

            'mobile' => [
                'nullable',
                'string',
                'max:20',
                Rule::unique('users', 'mobile')->ignore($customerId),
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
