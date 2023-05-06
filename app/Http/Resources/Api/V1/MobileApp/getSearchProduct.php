<?php

namespace App\Http\Resources\Api\V1\MobileApp;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class getSearchProduct extends JsonResource
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
            'old_price'=>$this->price,
            'current_price'=>$this->current_price,
            'has_discount'=>$this->discount_limit ? 1 : 0,
            'discount_percent'=>$this->discount_percent,
            'currency'=>$this->currency,
            'selected_sub_category_id'=>$this->sub_category_id,
            'currency_abbreviation'=>$this->currency_abbreviation,
            'wish_list_existence'=>isset($this->wishList) && !empty($this->wishList) && $this->wishList->count() ? 1 : 0,
            'product_cover'=>$this->product_cover ? $this->product_cover->url : '',
            'sub_categories_list'=>getSubCategoriesList($this->sub_category_id),
            'success'=>true
        ];
    }
}
