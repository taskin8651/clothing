<?php

namespace App\Http\Requests;

use App\Models\DeliveryTracking;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Symfony\Component\HttpFoundation\Response;

class UpdateDeliveryTrackingStatusRequest extends FormRequest
{
    public function authorize()
    {
        abort_if(Gate::denies('delivery_tracking_status_update'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return true;
    }

    public function rules()
    {
        return [
            'status' => ['required', Rule::in(array_keys(DeliveryTracking::STATUSES))],
            'failure_reason' => ['nullable', 'string', 'max:255'],
            'delivery_note' => ['nullable', 'string'],
            'admin_note' => ['nullable', 'string'],
        ];
    }
}
