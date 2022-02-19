<?php

namespace App\Http\Requests\v1;

use App\Models\State;
use App\Traits\ApiRequestAuthorization;

class StateRequest extends APIRequest
{
    use ApiRequestAuthorization;

    public function authorize()
    {
        return $this->authorizeRequestWithNestedResource(State::class, request()->route('company'));
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
