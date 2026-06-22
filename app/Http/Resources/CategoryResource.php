<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CategoryResource extends JsonResource
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
            'name' => $this->name,
            'todos_count' => $this->whenCounted('todos'), // si on fait $category->loadCount('todos')
            'todos' => TodoResource::collection($this->whenLoaded('todos')),
            'created_at' => $this->created_at->format('Y-m-d H:i:s'),
        ];
    }
}
