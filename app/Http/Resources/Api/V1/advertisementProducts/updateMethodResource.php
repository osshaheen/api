<?php

namespace App\Http\Resources\Api\V1\advertisementProducts;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class updateMethodResource extends JsonResource
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
            'is_active'=>$this->status == 1 ? true : false,
            'status'=>$this->status,
            'location'=>$this->advertisement_id,
            'product_url'=>'/api/products/'.$this->product_id,
            'product_id'=>$this->product_id,
            'product_name'=>$this->product_name,
            'product_title'=>$this->product_name,
            'product_price'=>$this->product_price,
            'product_quantity'=>$this->product_quantity,
            'product_extra_pictures'=>$this->product_extra_pictures,
            'product_cover'=>$this->product_cover ? $this->product_cover->url : '',
            'advertise_picture'=>$this->picture ? $this->picture->url : '',
            'success'=>true,
            'message'=>'تم اضافة المنتج للاعلان بنجاح'
        ];
    }
}
