<?php

namespace App\Http\Resources\Api\V1\newsletter;

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
            'email'=>$this->email,
            'message'=>'تم تعديل البريد بنجاح',
            'success'=>true
        ];
    }
}
