<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class PostSeeder extends Seeder
{
    public function run(): void
    {
        // نجيب أول category
        $category = DB::table('categories')
            ->where('slug', 'technology')
            ->first();

        DB::table('posts')->insert([
            [
                'user_id' => 1, // لازم يكون عندك user id 1
                'category_id' => $category?->id,
                'title' => 'My First Tech Post',
                'content' => 'This is a long content about technology.',
                'slug' => Str::slug('My First Tech Post'),
                'excerpt' => 'Tech post summary',
                'cover_image' => null,
                'status' => 'published',
                'views' => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => 1,
                'category_id' => $category?->id,
                'title' => 'Second Post in Tech',
                'content' => 'Another tech article content.',
                'slug' => Str::slug('Second Post in Tech'),
                'excerpt' => 'Second summary',
                'cover_image' => null,
                'status' => 'draft',
                'views' => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
