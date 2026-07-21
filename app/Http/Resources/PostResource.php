<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PostResource extends JsonResource
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
            'title' => $this->title,
            'content' => $this->content,
            'cover_url' => $this->thumbnail_url,
            'status' => [
                'name' => $this->status->value,
                'label' => $this->status->getLabel(),
                'color' => $this->status->getColor(),
            ],
            'published_at' => $this->when($this->published_at, $this->published_at),
            'category' => new CategoryResource($this->whenLoaded('category')),
            'author' => new UserResource($this->whenLoaded('user')),
        ];
    }
}
