<?php

namespace App\Http\Requests\v1;

use App\Models\Company;
use App\Traits\ApiRequestAuthorization;
use Illuminate\Validation\Rule;

class CompanyRequest extends APIRequest
{
    use ApiRequestAuthorization;

    public function authorize()
    {
        return $this->authorizeRequest(Company::class);
    }

    public function rules()
    {
        if(in_array($this->method(), ['POST','PATCH'])) {
            $rules = [
                'logo'          => '',
                'display_name'  => 'required',
                'app_name'      => '',
                'business_type' => '',
                'email'         => '',
                'address'       => '',
                'phone1'        => '',
                'phone2'        => '',
                'website'       => '',
                'options'       => '',
            ];
            if(!request()->route('company'))
            {
                $rules['name'] = [
                    'required',
                    Rule::unique('companies')->ignore(request()->route('company')),
                ];
            }
            return $rules;
        }
        return [];
    }
}
