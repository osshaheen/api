<?php

namespace App\Http\Resources\Api\V1\MobileApp;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class getAllNestedCategoriesClientArea extends JsonResource
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
            'picture'=>$this->picture->count() ? $this->picture[0]->url : '',
            'sub_categories'=>getAllNestedSubCategoriesClientArea::collection($this->sons)->response()->getData()
        ];
    }
}
