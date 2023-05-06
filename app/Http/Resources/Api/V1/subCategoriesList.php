<?php

namespace App\Http\Resources\Api\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class subCategoriesList extends JsonResource
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
            'father_name'=>$this->father_name,
            'admin_profit_percentage'=>$this->profit_percentage,
            'picture'=>$this->picture->count() ? $this->picture[0]->url : ''
        ];
    }
}
