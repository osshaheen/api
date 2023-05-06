<?php

namespace App\Http\Resources\Api\V1\MobileApp;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class getOrderDetails extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'order_number'=>'#'.$this->id,
            'client_name'=>$this->client_name,
            'street_address'=>$this->street_address,
            'status'=>$this->string_status,
            'payment_method'=>$this->string_payment_method,

            'total_price'=>$this->order_total_price,
            'total_price_to_be_paid'=>(float) ($this->order_total_price_after_promocode + $this->delivery_cost),
            'delivery_cost'=>(float)$this->delivery_cost,
            'is_coupon_applied'=>$this->promocode ? 1 : 0,
            'coupon_discount_value'=>$this->coupon_discount ? (float)$this->coupon_discount : 0,

            'products'=> getCartProductsFullData::collection($this->order_products)->response()->getData()
        ];
    }
}
