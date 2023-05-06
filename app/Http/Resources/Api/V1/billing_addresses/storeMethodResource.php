<?php

namespace App\Http\Resources\Api\V1\billing_addresses;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class storeMethodResource extends JsonResource
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
            'city'=>$this->city ? $this->city->name : '',
            'country'=>$this->city ? ($this->city->country ? $this->city->country->name : '') : '',
            'street_address'=>$this->street_address,
            'zip_code'=>$this->zip_code,
            'phone'=>$this->phone,
            'email'=>$this->email,
            'message'=>'تم اضافة العنوان بنجاح',
            'success'=>true
        ];
    }
}
