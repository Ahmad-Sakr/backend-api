<?php

namespace App\Http\Requests\v1;

use App\Models\Currency;
use App\Traits\ApiRequestAuthorization;

class CurrencyRequest extends APIRequest
{
    use ApiRequestAuthorization;

    public function authorize()
    {
        return $this->authorizeRequestWithNestedResource(Currency::class, request()->route('company'));
    }

    public function rules()
    {
        if(in_array($this->method(), ['POST','PATCH'])) {
            return [
                'data'        => 'required',
                'data.*.ref'  => 'required',
                'data.*.rate' => 'required',
            ];
        }
        return [];
    }
}
