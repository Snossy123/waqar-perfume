<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $local = app()->getLocale() ;

        
        return [
            'id' => $this->resource->id ?? null,
            'name' => $this->resource->{'name_'.$local} ?? '',
            'email' => $this->resource->email ?? null,
            'phone' => $this->resource->phone ?? '',
            'is_active' => $this->resource->is_active ?? false,
            'created_at' => $this->resource->created_at ?? null,
            'updated_at' => $this->resource->updated_at ?? null,
        ];
    }
}
