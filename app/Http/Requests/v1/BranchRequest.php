<?php

namespace App\Http\Requests\v1;

use App\Models\Branch;
use App\Traits\ApiRequestAuthorization;
use Illuminate\Validation\Rule;

class BranchRequest extends APIRequest
{
    use ApiRequestAuthorization;

    public function authorize()
    {
//        return $this->authorizeRequest(Branch::class);
        return $this->authorizeRequestWithNestedResource(Branch::class, request()->route('company'));
    }

    public function rules()
    {
        if(in_array($this->method(), ['POST','PATCH'])) {
            $rules = [
                'display_name'  => 'required',
                'email'         => '',
                'address'       => '',
                'phone1'        => '',
                'phone2'        => '',
                'options'       => '',
            ];
            if(!request()->route('branch'))
            {
                $rules['name'] = [
                    'required',
                    Rule::unique('branches')
                        ->where(function ($query) {
                            return $query->where('company_id', request()->route('company')->id);
                        }),
                ];
            }
            return $rules;
        }
        return [];
    }
}
