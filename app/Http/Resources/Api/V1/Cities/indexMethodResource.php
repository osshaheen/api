<?php

namespace App\Http\Resources\Api\V1\Cities;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class indexMethodResource extends JsonResource
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
            'delivery_cost'=>$this->delivery_cost,
            'currency_abbreviation'=>$this->country ? $this->country->currency_abbreviation : '',
            'country_name'=>$this->country ? $this->country->name : '',
            'country_id'=>$this->country ? $this->country->id : 0,
            'success'=>true
        ];
    }
}
