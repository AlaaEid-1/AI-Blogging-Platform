<?php

namespace Database\Factories;

use App\Enums\PostStatus;
use App\Models\Post;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Post>
 */
class PostFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $title = fake()->sentence(6);
        $slug = Str::slug($title).'-'.Str::random(5);

        return [
            'user_id' => null,
            'category_id' => null,
            'title' => $title,
            'slug' => $slug,
            'content' => fake()->paragraphs(4, true),
            'excerpt' => fake()->sentence(20),
            'cover_image' => null,
            'status' => PostStatus::Draft,
            'views' => fake()->numberBetween(0, 5000),
            'published_at' => null,
            'meta' => null,
        ];
    }

    /**
     * State for a published post.
     */
    public function published(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => PostStatus::Published,
            'published_at' => now()->subHour(),
        ]);
    }

    /**
     * State for a draft post.
     */
    public function draft(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => PostStatus::Draft,
            'published_at' => null,
        ]);
    }
}
