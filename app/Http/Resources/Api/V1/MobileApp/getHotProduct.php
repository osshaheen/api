<?php

namespace App\Http\Resources\Api\V1\MobileApp;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class getHotProduct extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $minutes_difference = Carbon::now()->diffInMinutes(Carbon::parse($this->end_date));
        $remained_minutes = $minutes_difference%60;
        $hours_difference = (int)($minutes_difference/60);
        $remained_hours = $hours_difference%24;
        $days_difference = (int)($hours_difference/24);
        return [
            'product_old_price'=>$this->product_price,
            'product_current_price'=>isset($this->discount_limit) && !empty($this->discount_limit) ? $this->current_price : $this->product_price,
            'product_id'=>$this->product_id,
            'product_name'=>$this->product_name,
            'product_quantity'=>$this->product_quantity,
            'product_cover'=>$this->product_cover ? $this->product_cover->url : '',
            'start_date'=>$this->start_date,
            'end_date'=>$this->end_date,
            'hours'=>$remained_hours,
            'minutes'=>$remained_minutes,
            'days'=>$days_difference
        ];
    }
}
