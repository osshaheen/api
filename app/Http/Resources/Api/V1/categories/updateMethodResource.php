<?php

namespace App\Http\Resources\Api\V1\categories;

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
            'level'=>$this->level,
            'father_id'=>$this->father_id,
            'father_category_name'=>$this->father ? $this->father->name : '',
            'admin_profit_percentage'=>$this->profit_percentage,
            'picture'=>$this->picture->count() ? $this->picture[0]->url : '',
            'message'=>'تم تعديل التصنيف بنجاح',
            'success'=>true
        ];
    }
}
