<?php

namespace App\Http\Requests\v1;


use Illuminate\Validation\Rule;

class RegisterRequest extends APIRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'username'      => [
                'required',
                'string',
                'max:50',
                Rule::unique('users')->ignore(request()->route('user')),
            ],
            'password'      => 'required|string|min:6',
            'first_name'    => 'required|string|max:50',
            'last_name'     => 'required|string|max:50',
            'email'         => [
                'required',
                'string',
                'email',
                Rule::unique('users')->ignore(request()->route('user')),
            ],
            'phone'         => 'sometimes|required|string',
            'plan_id'       => 'sometimes|required|exists:App\Models\Plan,id'
        ];
    }
}
