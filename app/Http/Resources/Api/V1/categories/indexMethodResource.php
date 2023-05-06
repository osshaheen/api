<?php

namespace App\Http\Resources\Api\V1\categories;

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
        if(isset($this->sons_count)){
            return [
                'id'=>$this->id,
                'name'=>$this->name,
                'level'=>$this->level,
                'admin_profit_percentage'=>$this->profit_percentage,
                'son_categories_count'=>$this->sons_count,
                'picture'=>$this->picture->count() ? $this->picture[0]->url : ''
            ];
        }else{
            return [
                'id'=>$this->id,
                'name'=>$this->name,
                'level'=>$this->level,
                'father_id'=>$this->father_id,
                'admin_profit_percentage'=>$this->profit_percentage,
                'picture'=>$this->picture->count() ? $this->picture[0]->url : ''
            ];
        }
    }
}
