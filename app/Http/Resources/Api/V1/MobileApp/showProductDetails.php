<?php

namespace App\Http\Resources\Api\V1\MobileApp;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class showProductDetails extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $ratings=getProductRatingPerClient($this->product_id);
//        dd($ratings);
//        if($this->product_id == 2){
//            dd(!empty($this->product->wishList),$this->product->wishList);
//        }
        return [
            'product_id'=>$this->id,
            'product_name'=>$this->title,
            'description'=>$this->description,
            'discount'=>(float)$this->discount_limit,
            'price'=>(float)$this->price,
            'current_price'=>(float)$this->current_price,
            'discount_percent'=>(float)$this->discount_percent,
            'pictures'=>\App\Http\Resources\Api\V1\products\productImagesResource::collection($this->pictures),
            'product_cover'=>$this->one_cover_picture ? $this->one_cover_picture->url : '',
            'currency'=>$this->currency,
            'currency_abbreviation'=>$this->currency_abbreviation,
            'available_quantity'=>$this->available_quantity,
            'rating'=>$ratings['rating'],
            'ratings_count'=>$ratings['ratings_count'],
            'wish_list_existance'=> (isset($this->wishList) && $this->wishList->count()) ? 1 : 0,
            'is_approved'=>$this->is_approved == 1 ? 'تم الموافقة عليه' : ($this->is_approved == 2 ? 'تم رفضه' : 'بانتظار موافقة الادارة'),
            'properties'=>\App\Http\Resources\Api\V1\CategoryPropertyValues\indexMethodResource::collection($this->properties_values),
            'success'=>true

        ];
    }
}
