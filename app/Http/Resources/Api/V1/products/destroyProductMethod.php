<?php

namespace App\Http\Resources\Api\V1\products;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class destroyProductMethod extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'=>$this->order_id,
            'order_num'=>'#'.$this->order_id,
            'order_date'=>$this->order ? $this->order->order_date : '',
            'client_name'=>$this->order ? $this->order->client_name : '',
            'client_mobile'=>$this->order ? $this->order->client_mobile : '',
        ];
    }
}
