<?php

namespace App\Http\Requests\v1;

use App\Models\Client;
use App\Traits\ApiRequestAuthorization;

class ClientRequest extends APIRequest
{
    use ApiRequestAuthorization;

    public function authorize()
    {
        return $this->authorizeRequestWithNestedResource(Client::class, request()->route('branch'));
    }

    public function rules()
    {
        if(in_array($this->method(), ['POST','PATCH'])) {
            return [
                'data'                  => 'required',
                'data.*.ref'            => 'required',
                'data.*.name'           => 'required',
                'data.*.company_name'   => '',
                'data.*.type'           => '',
                'data.*.phone1'         => '',
                'data.*.phone2'         => '',
                'data.*.mobile'         => '',
                'data.*.email'          => '',
                'data.*.website'        => '',
                'data.*.register_no1'   => '',
                'data.*.register_no2'   => '',
                'data.*.address'        => '',
                'data.*.country'        => '',
                'data.*.state'          => '',
                'data.*.currency'       => '',
                'data.*.balances'       => '',
            ];
        }
        return [];
    }
}
