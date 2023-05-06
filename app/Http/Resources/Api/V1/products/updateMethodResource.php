<?php

namespace App\Http\Resources\Api\V1\products;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class updateMethodResource extends JsonResource
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
            'title'=>$this->title,
            'description'=>$this->description,
            'sub_category_id'=>$this->sub_category_id,
            'price'=>$this->price,
            'current_price'=>$this->current_price,
            'discount_percent'=>$this->discount_percent,
            'seller_id'=>$this->seller_id,
            'currency'=>$this->currency,
            'currency_abbreviation'=>$this->currency_abbreviation,
            'category_name'=>$this->category ? $this->category->name : '',
            'category_level'=>$this->category ? $this->category->level : '',
            'father_category_name'=>$this->category ? $this->category->father_name : '',
            'seller_name'=>$this->seller ? $this->seller->name : '',
            'available_quantity'=>$this->available_quantity,
            'is_approved'=>$this->is_approved == 1 ? 'تم الموافقة عليه' : ($this->is_approved == 2 ? 'تم رفضه' : 'بانتظار موافقة الادارة'),
            'properties'=>\App\Http\Resources\Api\V1\CategoryPropertyValues\indexMethodResource::collection($this->properties_values),
            'pictures'=>\App\Http\Resources\Api\V1\products\productImagesResource::collection($this->pictures),
            'cover'=>$this->cover_picture->first() ? $this->cover_picture->first()->url : '',
            'message'=>'تم تعديل المنتج بنجاح',
            'success'=>true
        ];

    }
}
