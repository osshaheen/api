<?php

namespace App\Http\Resources\Api\V1\MobileApp;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class getCartProductsFullData extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $property = [];
        if($this->product && $this->product->properties_values->count()) {
            $property_first = $this->product->properties_values->first();
            $property = [
                'property_name'=>$property_first->property_name,
                'property_value'=>$property_first->value,
            ];
        }
        return [
            'product_id'=>$this->product_id,
            'order_product_id'=>$this->id,
            'product_name'=>$this->product_name,
            'product_cover'=>$this->product_cover ? $this->product_cover->url : '',
            'product_selling_price'=>$this->price,
            'product_selling_after_coupon_price'=>$this->product_selling_after_coupon_price,
            'currency'=>$this->currency,
            'currency_abbreviation'=>$this->currency_abbreviation,
            'quantity'=>$this->order_quantity,
            'property'=>$property
        ];
    }
}
