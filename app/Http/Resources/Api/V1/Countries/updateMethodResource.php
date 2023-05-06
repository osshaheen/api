<?php

namespace App\Http\Resources\Api\V1\Countries;

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
            'name'=>$this->name,
            'currency'=>$this->currency,
            'currency_abbreviation'=>$this->currency_abbreviation,
            'message'=>'تم تعديل الدولة بنجاح',
            'success'=>true
        ];
    }
}
