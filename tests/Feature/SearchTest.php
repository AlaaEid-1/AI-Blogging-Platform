<?php

namespace Tests\Feature;

use App\Enums\PostStatus;
use App\Models\Category;
use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Tests\TestCase;

class SearchTest extends TestCase
{
    use RefreshDatabase;

    private User $user;

    private Category $category;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();
        $this->category = Category::create([
            'name' => 'Technology',
            'slug' => 'technology',
        ]);
    }

    /**
     * Test accessing search page without a query.
     */
    public function test_user_can_access_search_page_without_query(): void
    {
        $response = $this->get(route('search'));

        $response->assertStatus(200);
        $response->assertViewHas('posts');
        $response->assertViewHas('query', null);
    }

    /**
     * Test searching posts by title.
     */
    public function test_user_can_search_posts_by_title(): void
    {
        Post::create([
            'user_id' => $this->user->id,
            'category_id' => $this->category->id,
            'title' => 'Unique Laravel Tutorial',
            'content' => 'General content here.',
            'slug' => Str::slug('Unique Laravel Tutorial'),
            'excerpt' => 'Excerpt...',
            'status' => PostStatus::Published,
        ]);

        Post::create([
            'user_id' => $this->user->id,
            'category_id' => $this->category->id,
            'title' => 'Django Tutorial',
            'content' => 'General content here.',
            'slug' => Str::slug('Django Tutorial'),
            'excerpt' => 'Excerpt...',
            'status' => PostStatus::Published,
        ]);

        $response = $this->get(route('search', ['query' => 'Laravel']));

        $response->assertStatus(200);
        $response->assertSee('Unique Laravel Tutorial');
        $response->assertDontSee('Django Tutorial');
    }

    /**
     * Test searching posts by content.
     */
    public function test_user_can_search_posts_by_content(): void
    {
        Post::create([
            'user_id' => $this->user->id,
            'category_id' => $this->category->id,
            'title' => 'First Post',
            'content' => 'This content features the word antigravity specifically.',
            'slug' => Str::slug('First Post'),
            'excerpt' => 'Excerpt...',
            'status' => PostStatus::Published,
        ]);

        Post::create([
            'user_id' => $this->user->id,
            'category_id' => $this->category->id,
            'title' => 'Second Post',
            'content' => 'This is regular text.',
            'slug' => Str::slug('Second Post'),
            'excerpt' => 'Excerpt...',
            'status' => PostStatus::Published,
        ]);

        $response = $this->get(route('search', ['query' => 'antigravity']));

        $response->assertStatus(200);
        $response->assertSee('First Post');
        $response->assertDontSee('Second Post');
    }

    /**
     * Test draft posts are excluded from search.
     */
    public function test_draft_posts_are_excluded_from_search(): void
    {
        Post::create([
            'user_id' => $this->user->id,
            'category_id' => $this->category->id,
            'title' => 'Draft Secret Tutorial',
            'content' => 'Some text.',
            'slug' => Str::slug('Draft Secret Tutorial'),
            'excerpt' => 'Excerpt...',
            'status' => PostStatus::Draft,
        ]);

        $response = $this->get(route('search', ['query' => 'Secret']));

        $response->assertStatus(200);
        $response->assertDontSee('Draft Secret Tutorial');
    }
}
