<?php

namespace App\Http\Resources\Api\V1\Countries;

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
        return [
            'id' => $this->id,
            'name'=>$this->name,
            'currency'=>$this->currency,
            'abbreviation'=>$this->currency_abbreviation,
            'success'=>true
        ];
    }
}
