<?php

namespace App\Http\Resources\v1;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class CompanyResource extends JsonResource
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
            'name'          => $this->name,
            'logo'          => $this->logo ? Storage::url($this->logo) : '',
            'display_name'  => $this->display_name,
            'app_name'      => $this->app_name,
            'business_type' => $this->business_type,
            'email'         => $this->email,
            'address'       => $this->address,
            'phone1'        => $this->phone1,
            'phone2'        => $this->phone2,
            'website'       => $this->website,
            'user_id'       => $this->user_id,
            'user'          => $this->user->name,
            'options'       => $this->options,
        ];
    }
}
