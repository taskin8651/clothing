<?php

namespace App\Http\Requests;

use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Symfony\Component\HttpFoundation\Response;

class UpdateDeliveryBoyRequest extends FormRequest
{
    public function authorize()
    {
        abort_if(Gate::denies('delivery_boy_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return true;
    }

    public function rules()
    {
        $deliveryBoyId = $this->route('deliveryBoy') ? $this->route('deliveryBoy')->id : null;

        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['nullable', 'email', 'max:255', Rule::unique('delivery_boys', 'email')->ignore($deliveryBoyId)],
            'mobile' => ['nullable', 'string', 'max:20', Rule::unique('delivery_boys', 'mobile')->ignore($deliveryBoyId)],
            'password' => ['nullable', 'string', 'min:6'],
            'address' => ['nullable', 'string'],
            'city' => ['nullable', 'string', 'max:255'],
            'area' => ['nullable', 'string', 'max:255'],
            'pincode' => ['nullable', 'string', 'max:20'],
            'vehicle_type' => ['nullable', 'string', 'max:255'],
            'vehicle_number' => ['nullable', 'string', 'max:255'],
            'id_proof_type' => ['nullable', 'string', 'max:255'],
            'status' => ['nullable', 'boolean'],
            'profile_image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
            'id_proof_image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
            'remove_profile_image' => ['nullable', 'boolean'],
            'remove_id_proof_image' => ['nullable', 'boolean'],
        ];
    }
}
