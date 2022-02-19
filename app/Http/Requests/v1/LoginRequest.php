<?php

namespace App\Http\Requests\v1;

class LoginRequest extends APIRequest
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
            'username'  => 'required|string|max:50|exists:App\Models\User,username',
            'password'  => 'required|string|min:6',
        ];
    }
}
