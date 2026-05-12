<?php

namespace App\Http\Requests;

class UpdateReceiptRequest extends StoreReceiptRequest
{
    public function authorize()
    {
        abort_if(\Gate::denies('receipt_edit'), \Symfony\Component\HttpFoundation\Response::HTTP_FORBIDDEN, '403 Forbidden');
        return true;
    }
}
