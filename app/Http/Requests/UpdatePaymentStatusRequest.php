<?php

namespace App\Http\Requests;

use App\Models\Payment;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Symfony\Component\HttpFoundation\Response;

class UpdatePaymentStatusRequest extends FormRequest
{
    public function authorize()
    {
        abort_if(Gate::denies('payment_status_update'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return true;
    }

    public function rules()
    {
        return [
            'status' => ['required', Rule::in(array_keys(Payment::STATUSES))],
            'clear_paid_at' => ['nullable', 'boolean'],
            'admin_note' => ['nullable', 'string'],
        ];
    }
}
