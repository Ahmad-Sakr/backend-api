<?php

namespace App\Http\Resources\v1;

use Illuminate\Http\Resources\Json\JsonResource;

class ClientResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id'            => $this->id,
            'ref'           => $this->ref,
            'name'          => $this->name,
            'company_name'  => $this->company_name,
            'type'          => $this->type,
            'phone1'        => $this->phone1,
            'phone2'        => $this->phone2,
            'mobile'        => $this->mobile,
            'email'         => $this->email,
            'website'       => $this->website,
            'register_no1'  => $this->register_no1,
            'register_no2'  => $this->register_no2,
            'address'       => $this->address,
            'custom_fields' => $this->custom_fields,
            'company_id'    => $this->company_id,
            'company'       => $this->company->display_name,
            'branch_id'     => $this->branch_id,
            'branch'        => $this->branch->display_name,
            'country_id'    => $this->country_id,
            'country'       => ($this->country_id) ? $this->country->name : '',
            'state_id'      => $this->state_id,
            'state'         => ($this->state_id) ? $this->state->name : '',
            'currency_id'   => $this->currency_id,
            'currency'      => ($this->currency_id) ? $this->currency->ref : '',
            'balances'      => BalanceResource::collection($this->balances)->toArray(null)
        ];
    }
}
