<?php

namespace App\Http\Requests\v1;

use App\Models\Country;
use App\Traits\ApiRequestAuthorization;

class CountryRequest extends APIRequest
{
    use ApiRequestAuthorization;

    public function authorize()
    {
        return $this->authorizeRequestWithNestedResource(Country::class, request()->route('company'));
    }

    public function rules()
    {
        if(in_array($this->method(), ['POST','PATCH'])) {
            return [
                'data'        => 'required',
                'data.*.ref'  => 'required',
                'data.*.name' => 'required',
            ];
        }
        return [];
    }
}
