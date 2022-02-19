<?php

namespace App\Http\Requests\v1;


use App\Models\Plan;
use App\Traits\ApiRequestAuthorization;
use Illuminate\Validation\Rule;

class PlanRequest extends APIRequest
{
    use ApiRequestAuthorization;

    public function authorize()
    {
        return $this->authorizeRequest(Plan::class);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        if(in_array($this->method(), ['POST','PATCH']))
        {
            return [
                'name' => [
                    'required',
                    Rule::unique('plans')->ignore(request()->route('plan')),
                ],
                'display_name'  => 'required',
                'price_monthly' => 'required',
                'price_annual'  => 'required',
                'options'       => '',
            ];
        }
        return [];
    }
}
