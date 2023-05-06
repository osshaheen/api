<?php

namespace App\Http\Resources\Api\V1\MobileApp;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class getBestSellerProductsClientArea extends JsonResource
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
            'id'=>$this->id,
//            'section_name'=>$this->section_name,
            'product_id'=>$this->product_id,
            'product_name'=>$this->product_name,
//            'product_old_price'=>$this->product_price,
            'price'=>$this->product_price,
            'discount'=>isset($this->discount_limit) && !empty($this->discount_limit) ? $this->discount_limit : 0,
            'currency'=>$this->currency,
            'currency_abbreviation'=>$this->currency_abbreviation,
            'available_quantity'=>$this->product_quantity,
            'rating'=>$ratings['rating'],
            'ratings_count'=>$ratings['ratings_count'],
            'wish_list_existance'=>isset($this->product) && !empty($this->product) ? (isset($this->product->wishList) && $this->product->wishList->count() ? 1 : 0) : 0,
            'product_cover'=>$this->product_cover ? $this->product_cover->url : '',
            'success'=>true
        ];
    }
}
