<?php

namespace App\Http\Resources\Api\V\Countries;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class destroyCountryMethod extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        if(isset($this->email)) {
            return [
                'id'=>$this->id,
                'name'=>$this->name,
                'email'=>$this->email,
                'mobile'=>$this->mobile,
                'type'=>$this->type,
                'blocked'=>$this->blocked,
                'type_name'=>($this->type == 1) ? 'تاجر': (($this->type == 2) ? 'مشتري': 'مدير'),
                'address'=>$this->address,
                'country_id'=>$this->country_id,
                'country_name'=>$this->country ? $this->country->name : '',
                'profile_picture'=>$this->adminPicture->count() ? $this->adminPicture[0]->url : '',
                'success'=>true
            ];
        }else{
            return [
                'id'=>$this->id,
                'name'=>$this->name,
                'delivery_cost'=>$this->delivery_cost,
                'currency_abbreviation'=>$this->country ? $this->country->currency_abbreviation : '',
                'country_name'=>$this->country ? $this->country->name : '',
                'country_id'=>$this->country ? $this->country->id : 0,
                'success'=>true
            ];
        }
    }
}
