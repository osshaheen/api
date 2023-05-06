<?php

namespace App\Http\Resources\Api\V1\MobileApp;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class filterProductsByCategory extends JsonResource
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
            'product_cover'=>$this->product_cover ? $this->product_cover->url : '',
            'price'=>$this->price,
            'quantity'=>$this->available_quantity,
            'availability'=>$this->available_quantity ? 1 : 0,
            'discount'=>$this->discount_limit ? $this->discount_limit : 0,
            'has_discount'=>$this->discount_limit ? 1 : 0,
            'rating'=>$ratings['rating'],
            'ratings_count'=>$ratings['ratings_count'],
            'currency'=>$this->currency,
            'currency_abbreviation'=>$this->currency_abbreviation,
            'success'=>true
        ];
    }
}
