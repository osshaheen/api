<?php

namespace App\Http\Resources\Api\V\cities;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class destroyCity extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'=>$this->id,
            'first_name'=>$this->first_name,
            'last_name'=>$this->last_name,
            'company_name'=>$this->company_name,
            'city_id'=>$this->city_id,
            'city_name'=>$this->city_name,
            'city_delivery_cost'=>$this->city_delivery_cost,
            'street_address'=>$this->street_address,
            'zip_code'=>$this->zip_code,
            'string_status'=>$this->string_status,
            'phone'=>$this->phone,
            'email'=>$this->email
        ];
    }
}
