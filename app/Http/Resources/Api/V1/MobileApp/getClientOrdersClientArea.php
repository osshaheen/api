<?php

namespace App\Http\Resources\Api\V1\MobileApp;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class getClientOrdersClientArea extends JsonResource
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
            'order_num'=>'#'.$this->id,
            'order_status'=>'#'.$this->string_status,
            'order_date'=>$this->order_date,
            'client_name'=>$this->client_name,
            'client_mobile'=>$this->client_mobile,
            'address'=>new \App\Http\Resources\Api\V1\addresses\showMethodResource($this->address),
            'billingAddress'=>new \App\Http\Resources\Api\V1\addresses\showMethodResource($this->billingAddress),
            'products'=>\App\Http\Resources\Api\V1\orders\showProductListMethodResource::collection($this->order_products),
            'delivery_cost'=>(float)$this->delivery_cost,
            'order_total_price'=>$this->order_total_price_after_promocode,
            'admin_fees'=>(float)$this->admin_fees,
            'vendor_revenues'=>$this->order_total_price_after_promocode - (float)$this->admin_fees,
            'price_to_be_paid_by_client'=>$this->order_total_price_after_promocode + $this->delivery_cost,
            'promocode_discount'=>$this->promocode ? $this->promocode->discount_percentage : 0,
            'is_vendor_revenues_paid'=>$this->is_vendor_revenues_paid
        ];
    }
}
