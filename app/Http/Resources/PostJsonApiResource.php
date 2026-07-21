<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\JsonApi\JsonApiResource;

class PostJsonApiResource extends JsonApiResource
{
    /**
     * The resource's attributes.
     */
    public $attributes = [
        'title',
        'content',
        'thumbnail_url',
        'published_at',
    ];

    /**
     * The resource's relationships.
     */
    public $relationships = [
        // 'category',
        // 'user',
    ];

    /**
     * Get the resource's relationships.
     */
    public function toRelationships(Request $request): array
    {
        return [
            'category' => CategoryJsonApiResource::class,
            'user' => UserJsonApiResource::class,
        ];
    }

    /**
     * Get the resource's type.
     */
    public function toType(Request $request): string
    {
        return 'posts';
    }
}
