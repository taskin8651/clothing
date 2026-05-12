<?php

namespace App\Http\Requests;

use App\Models\Payment;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Symfony\Component\HttpFoundation\Response;

class UpdatePaymentRequest extends FormRequest
{
    public function authorize()
    {
        abort_if(Gate::denies('payment_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return true;
    }

    public function rules()
    {
        return [
            'order_id' => ['required', 'exists:orders,id'],
            'payment_method' => ['required', Rule::in(array_keys(Payment::METHODS))],
            'payment_gateway' => ['nullable', 'string', 'max:255'],
            'transaction_id' => ['nullable', 'string', 'max:255'],
            'amount' => ['required', 'numeric', 'min:0'],
            'status' => ['required', Rule::in(array_keys(Payment::STATUSES))],
            'paid_at' => ['nullable', 'date'],
            'gateway_response' => ['nullable'],
        ];
    }
}
