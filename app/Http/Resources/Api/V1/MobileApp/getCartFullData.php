<?php

namespace App\Http\Resources\Api\V1\MobileApp;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class getCartFullData extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'client_name'=>$this->client_name,
            'total_price'=>$this->order_total_price_after_promocode,
            'products'=> getCartProductsFullData::collection($this->order_products)->response()->getData(),
            'success'=>true
        ];
    }
}
