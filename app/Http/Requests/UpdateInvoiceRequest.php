<?php

namespace App\Http\Requests;

class UpdateInvoiceRequest extends StoreInvoiceRequest
{
    public function authorize()
    {
        abort_if(\Gate::denies('invoice_edit'), \Symfony\Component\HttpFoundation\Response::HTTP_FORBIDDEN, '403 Forbidden');
        return true;
    }
}
