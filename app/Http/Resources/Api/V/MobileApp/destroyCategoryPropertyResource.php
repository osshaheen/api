<?php

namespace App\Http\Resources\Api\V\MobileApp;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class destroyCategoryPropertyResource extends JsonResource
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
            'property_name'=>$this->property_name,
            'property_value'=>$this->value,
            'product_id'=>$this->product_id,
            'product_name'=>$this->product_name
        ];
    }
}
