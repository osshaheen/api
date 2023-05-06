<?php

namespace App\Http\Resources\Api\V1\orders;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class showProductListMethodResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $available_product_quantity = $this->product_quantity;
        return [
            'product_id'=>$this->product_id,
            'quantity'=>$this->order_quantity,
            'product_name'=>$this->product_name,
            'product_title'=>$this->product_name,
            'product_price'=>$this->price,
            'product_quantity'=>$this->order_quantity,
            'available_product_quantity'=>$available_product_quantity,
            'product_quantity_existence'=>$this->order_quantity > $available_product_quantity ? 'لا تكفي' : 'تكفي',
            'currency'=>$this->currency,
            'currency_abbreviation'=>$this->currency_abbreviation,
            'product_extra_pictures'=>$this->product_extra_pictures,
            'product_cover'=>$this->product_cover,
            'seller_name'=>$this->seller_name,
            'seller_mobile'=>$this->seller_mobile,
            'seller_country'=>$this->seller_country,
        ];
    }
}
