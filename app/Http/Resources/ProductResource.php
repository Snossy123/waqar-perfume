<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {    
        return [
            'id' => $this->resource->id ?? null,
            'name' => $this->resource->name ?? '',
            'description' => $this->resource->description ?? null,
            'price' => $this->resource->price ?? null,
            'discounted_price' => $this->resource->discounted_price ?? null,
            'stars' => $this->resource->stars ?? null,
            'offer_end_date' => $this->resource->offer_end_date ?? null,
            'stock' => $this->resource->stock ?? null,
            'category' => $this->resource->category ?? [],
            'images' => $this->resource->images ?? [],
            'created_at' => $this->resource->created_at ?? null,
            'updated_at' => $this->resource->updated_at ?? null,
        ];
    }
}
