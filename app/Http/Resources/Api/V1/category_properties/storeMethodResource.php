<?php

namespace App\Http\Resources\Api\V1\category_properties;

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
            'property_name'=>$this->property_name,
            'category_id'=>$this->category_id,
            'category'=>$this->category ? $this->category->name : null,
            'category_level'=>$this->category ? $this->category->level : null,
            'message'=>'تم اضافة خاصية التصنيف بنجاح',
            'success'=>true
        ];
    }
}
