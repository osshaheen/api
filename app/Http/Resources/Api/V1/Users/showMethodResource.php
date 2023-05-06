<?php

namespace App\Http\Resources\Api\V1\Users;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class showMethodResource extends JsonResource
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
            'name'=>$this->name,
            'email'=>$this->email,
            'mobile'=>$this->mobile,
            'blocked'=>$this->blocked,
            'type'=>$this->type,
            'type_name'=>'مدير',
            'address'=>$this->address,
            'country_id'=>$this->country_id,
            'country_name'=>$this->country ? $this->country->name : '',
            'profile_picture'=>$this->adminPicture->count() ? $this->adminPicture[0]->url : '',
            'success'=>true
        ];
    }
}
