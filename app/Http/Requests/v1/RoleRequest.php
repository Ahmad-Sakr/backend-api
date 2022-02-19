<?php

namespace App\Http\Requests\v1;


use App\Models\Role;
use App\Traits\ApiRequestAuthorization;
use Illuminate\Validation\Rule;

class RoleRequest extends APIRequest
{
    use ApiRequestAuthorization;

    public function authorize()
    {
        return $this->authorizeRequest(Role::class);
    }

    public function rules()
    {
        if(in_array($this->method(), ['POST','PATCH']))
        {
            return [
                'name' => [
                    'required',
                    Rule::unique('roles')->ignore(request()->route('role')),
                ],
            ];
        }
        return [];
    }
}
