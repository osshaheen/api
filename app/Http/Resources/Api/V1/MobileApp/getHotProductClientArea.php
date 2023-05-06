<?php

namespace App\Http\Resources\Api\V1\MobileApp;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class getHotProductClientArea extends JsonResource
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
            'price'=>$this->product_price,
            'discount'=>isset($this->discount_limit) && !empty($this->discount_limit) ? $this->discount_limit : $this->product_price,
            'product_id'=>$this->product_id,
            'product_name'=>$this->product_name,
            'available_quantity'=>$this->product_quantity,
            'rating'=>$ratings['rating'],
            'ratings_count'=>$ratings['ratings_count'],
            'product_cover'=>$this->product_cover ? $this->product_cover->url : '',
            'start_date'=>$this->start_date,
            'end_date'=>$this->end_date,
            'hours'=>Carbon::parse($this->start_date)->diffInHours(Carbon::parse($this->end_date)),
            'minutes'=>Carbon::parse($this->start_date)->diffInMinutes(Carbon::parse($this->end_date)),
            'days'=>Carbon::parse($this->start_date)->diffInDays(Carbon::parse($this->end_date))
        ];
    }
}
