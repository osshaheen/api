<?php

namespace App\Http\Resources\Api\V1\MobileApp;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class getNewProducts extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
//        dd(isset($this->wishList) && !empty($this->wishList) ,$this->wishList);
        return [
            'product_id'=>$this->id,
            'product_name'=>$this->title,
            'price'=>$this->current_price,
            'currency'=>$this->currency,
            'currency_abbreviation'=>$this->currency_abbreviation,
            'wish_list_existance'=>isset($this->wishList) && !empty($this->wishList) && $this->wishList->count() ? 1 : 0,
            'product_cover'=>$this->product_cover ? $this->product_cover->url : '',
            'success'=>true
        ];
    }
}
