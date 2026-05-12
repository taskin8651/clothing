<?php

namespace App\Http\Requests;

use App\Models\ReturnRequest;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Symfony\Component\HttpFoundation\Response;

class UpdateReturnStatusRequest extends FormRequest
{
    public function authorize()
    {
        abort_if(Gate::denies('return_request_status_update'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        return true;
    }

    public function rules()
    {
        return [
            'status' => ['required', Rule::in(array_keys(ReturnRequest::STATUSES))],
            'admin_note' => ['nullable', 'string'],
        ];
    }
}
