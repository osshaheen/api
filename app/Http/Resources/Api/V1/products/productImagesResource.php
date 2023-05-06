<?php

namespace App\Http\Resources\Api\V1\products;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class productImagesResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'url'=>$this->url,
            'cover'=>$this->is_cover
        ];
    }
}
