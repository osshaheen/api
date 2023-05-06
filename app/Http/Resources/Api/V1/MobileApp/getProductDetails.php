<?php

namespace App\Http\Resources\Api\V1\MobileApp;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class getProductDetails extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $ratings=getProductRatingPerClient($this->product_id);

        return [
            'product_id'=>$this->id,
            'product_name'=>$this->title,
            'wish_list_existence'=>isset($this->wishList) && !empty($this->wishList) && $this->wishList->count() ? 1 : 0,
            'description'=>$this->description,
            'rating'=>$ratings['rating'],
            'ratings_count'=>$ratings['ratings_count'],
            'old_price'=>$this->price,
            'current_price'=>$this->current_price,
            'has_discount'=>$this->discount_limit ? 1 : 0,
            'discount_percent'=>$this->discount_percent,
            'properties'=>\App\Http\Resources\Api\V1\CategoryPropertyValues\indexMethodResource::collection($this->properties_values),
            'pictures'=>\App\Http\Resources\Api\V1\products\productImagesResource::collection($this->pictures),
            'cover'=>$this->cover_picture->first() ? $this->cover_picture->first()->url : '',
            'success'=>true
        ];
    }
}
