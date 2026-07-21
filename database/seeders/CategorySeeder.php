<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        DB::table('categories')->insert([
            [
                'name' => 'Technology',
                'slug' => 'technology',
                'description' => 'Tech posts',
                'parent_id' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Programming',
                'slug' => 'programming',
                'description' => 'Programming posts',
                'parent_id' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
