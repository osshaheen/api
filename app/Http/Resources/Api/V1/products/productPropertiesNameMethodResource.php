<?php

namespace App\Http\Resources\Api\V1\products;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class productPropertiesNameMethodResource extends JsonResource
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

//            'title'=>$this->title,
//            'description'=>$this->description,
//            'sub_category_id'=>$this->sub_category_id,
//            'seller_id'=>$this->seller_id,
//            'category_name'=>$this->category ? $this->category->name : '',
//            'category_level'=>$this->category ? $this->category->level : '',
//            'father_category_name'=>$this->category ? $this->category->father_name : '',
//            'seller_name'=>$this->seller ? $this->seller->name : '',
//            'available_quantity'=>$this->available_quantity,
//            'is_approved'=>$this->is_approved == 1 ? 'تم الموافقة عليه' : ($this->is_approved == 2 ? 'تم رفضه' : 'بانتظار موافقة الادارة')
        ];
    }
}
