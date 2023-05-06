<?php

namespace App\Http\Resources\Api\V1\MobileApp;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class getClientWishList extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $product = $this->product;
        return [
            'product_id'=>$product->id,
            'product_name'=>$product->title,
            'old_price'=>$product->price,
            'current_price'=>$product->current_price,
            'has_discount'=>$product->discount_limit ? 1 : 0,
            'discount_percent'=>$product->discount_percent,
            'currency'=>$product->currency,
            'selected_sub_category_id'=>$product->sub_category_id,
            'currency_abbreviation'=>$product->currency_abbreviation,
            'product_cover'=>$product->product_cover ? $product->product_cover->url : '',
            'success'=>true
        ];
    }
}
