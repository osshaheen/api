<?php

namespace App\Http\Resources\Api\V1\MobileApp;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class getSearchProductClientArea extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'product_id'=>$this->id,
            'product_name'=>$this->title,
            'price'=>$this->price,
            'discount'=>$this->discount_limit ? $this->discount_limit : 0,
            'has_discount'=>$this->discount_limit ? 1 : 0,
            'discount_percent'=>$this->discount_percent,
            'currency'=>$this->currency,
            'selected_sub_category_id'=>$this->sub_category_id,
            'selected_sub_category_name'=>$this->category_name,
            'selected_sub_category_father_name'=>$this->category_father_name,
            'currency_abbreviation'=>$this->currency_abbreviation,
            'wish_list_existence'=>isset($this->wishList) && !empty($this->wishList) && $this->wishList->count() ? 1 : 0,
            'product_cover'=>$this->product_cover ? $this->product_cover->url : '',
            'success'=>true
        ];
    }
}
