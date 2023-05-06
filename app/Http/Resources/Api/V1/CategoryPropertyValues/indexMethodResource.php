<?php

namespace App\Http\Resources\Api\V1\CategoryPropertyValues;

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
            'property_id'=>$this->property_id,
            'product_id'=>$this->product_id,
            'property_name'=>$this->property ? $this->property->property_name : '',
            'product_title'=>$this->product ? $this->product->title : '',
            'value'=>$this->value
        ];
    }
}
