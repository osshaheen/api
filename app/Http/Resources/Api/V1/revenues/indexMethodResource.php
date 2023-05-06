<?php

namespace App\Http\Resources\Api\V1\revenues;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class indexMethodResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
//        dd($this->resource);
        $total_revenue = (float)$this->resource->total_price - ($this->resource->total_price*$this->resource->discount_percentage1);
        $admin_fees = (float)($this->resource->admin_profit_percentage/100)*$total_revenue;
        return [
//            'order_id' => (int) $this->order_id,
            'product_title' => $this->resource->product_title,
            'client_name' => $this->resource->client_name,
            'quantity' => (int) $this->resource->quantity,
            'product_price' => (float) $this->resource->product_price,
            'total_price' => (float) $this->resource->total_price,
            'discount_percentage' => (float) $this->resource->discount_percentage1,
            'admin_fees' => $admin_fees,
            'total_revenue' => $total_revenue,
            'vendor_revenue' =>  $total_revenue - $admin_fees
        ];
    }
}
