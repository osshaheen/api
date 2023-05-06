<?php

namespace App\Http\Resources\Api\V1\MobileApp;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class getBestSellerProducts extends JsonResource
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
            'section_name'=>$this->section_name,
            'product_id'=>$this->product_id,
            'product_name'=>$this->product_name,
            'product_old_price'=>$this->product_price,
            'product_current_price'=>$this->current_price,
            'currency'=>$this->currency,
            'currency_abbreviation'=>$this->currency_abbreviation,
            'rating'=>$ratings['rating'],
            'ratings_count'=>$ratings['ratings_count'],
            'wish_list_existance'=>isset($this->product) && !empty($this->product) ? (isset($this->product->wishList) && $this->product->wishList->count() ? 1 : 0) : 0,
            'product_cover'=>$this->product_cover ? $this->product_cover->url : '',
            'success'=>true
        ];
    }
}
