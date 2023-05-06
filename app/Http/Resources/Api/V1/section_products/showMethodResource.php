<?php

namespace App\Http\Resources\Api\V1\section_products;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class showMethodResource extends JsonResource
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
            'is_active'=>$this->status == 1 ? true : (Carbon::now()->between(Carbon::parse($this->start_date),Carbon::parse($this->end_date)) ? true : false),
            'status'=>$this->status,
            'start_date'=>$this->start_date,
            'end_date'=>$this->end_date,
            'section_name'=>$this->section_name,
            'product_id'=>$this->product_id,
            'product_name'=>$this->product_name,
            'product_description'=>$this->product_description,
            'product_quantity'=>$this->product_quantity,
            'product_price'=>$this->product_price,
            'product_cover'=>$this->product_cover ? $this->product_cover->url : '',
            'product_extra_pictures'=>$this->product_extra_pictures,
            'success'=>true
        ];
    }
}
