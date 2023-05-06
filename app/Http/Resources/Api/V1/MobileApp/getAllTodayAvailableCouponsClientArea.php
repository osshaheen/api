<?php

namespace App\Http\Resources\Api\V1\MobileApp;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class getAllTodayAvailableCouponsClientArea extends JsonResource
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
            'title'=>$this->title,
            'is_active'=>$this->status == 1 ? true : (Carbon::now()->between(Carbon::parse($this->start_date),Carbon::parse($this->end_date)) ? true : false),
            'status'=>$this->status,
            'start_date'=>$this->start_date,
            'end_date'=>$this->end_date,
            'discount_percentage'=>(float)$this->discount_percentage,
            'done_orders_count'=>$this->orders_count,
            'success'=>true
        ];
    }
}
