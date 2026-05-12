<?php

namespace App\Http\Requests;

use App\Models\Receipt;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Symfony\Component\HttpFoundation\Response;

class StoreReceiptRequest extends FormRequest
{
    public function authorize()
    {
        abort_if(Gate::denies('receipt_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        return true;
    }

    public function rules()
    {
        return [
            'invoice_id' => ['nullable', 'exists:invoices,id'],
            'order_id' => ['nullable', 'exists:orders,id'],
            'payment_id' => ['nullable', 'exists:payments,id'],
            'customer_id' => ['nullable', 'exists:users,id'],
            'shop_id' => ['nullable', 'exists:shops,id'],
            'receipt_type' => ['required', Rule::in(array_keys(Receipt::TYPES))],
            'receipt_date' => ['nullable', 'date'],
            'payment_method' => ['nullable', 'string', 'max:50'],
            'payment_gateway' => ['nullable', 'string', 'max:255'],
            'transaction_id' => ['nullable', 'string', 'max:255'],
            'amount' => ['required', 'numeric', 'min:0'],
            'status' => ['required', Rule::in(array_keys(Receipt::STATUSES))],
            'received_from' => ['nullable', 'string', 'max:255'],
            'received_by' => ['nullable', 'string', 'max:255'],
            'notes' => ['nullable', 'string'],
            'admin_note' => ['nullable', 'string'],
        ];
    }
}
