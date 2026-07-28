<?php

namespace Tests\Feature;

use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class UserProfileTest extends TestCase
{
    use RefreshDatabase;

    // -------------------------------------------------------------------------
    // Public visibility
    // -------------------------------------------------------------------------

    #[Test]
    public function guest_can_view_a_public_profile_page(): void
    {
        $user = User::factory()->create([
            'name' => 'Alaa Eid',
            'username' => 'Alaa_Eid',
            'bio' => 'AI blogger and developer.',
        ]);

        $response = $this->get(route('profile.show', $user->username));

        $response->assertOk();
        $response->assertSee('Alaa Eid');
        $response->assertSee('@Alaa_Eid');
        $response->assertSee('AI blogger and developer.');
    }

    #[Test]
    public function profile_returns_404_for_nonexistent_username(): void
    {
        $response = $this->get(route('profile.show', 'this_user_does_not_exist'));

        $response->assertNotFound();
    }

    // -------------------------------------------------------------------------
    // Stats
    // -------------------------------------------------------------------------

    #[Test]
    public function profile_page_displays_published_posts_count(): void
    {
        $user = User::factory()->create(['username' => 'writer123']);

        $user->posts()->saveMany(Post::factory()->count(3)->published()->make());

        // Draft should NOT count
        $user->posts()->save(Post::factory()->draft()->make());

        $response = $this->get(route('profile.show', $user->username));

        $response->assertOk();
        $response->assertSee('3');
    }

    // -------------------------------------------------------------------------
    // Action buttons
    // -------------------------------------------------------------------------

    #[Test]
    public function profile_owner_sees_edit_profile_button_not_follow(): void
    {
        $user = User::factory()->create(['username' => 'myuser']);

        $response = $this->actingAs($user)->get(route('profile.show', $user->username));

        $response->assertOk();
        $response->assertSee('Edit Profile');
        $response->assertDontSee('person_add');
    }

    #[Test]
    public function authenticated_visitor_sees_follow_button_when_not_following(): void
    {
        $profileOwner = User::factory()->create(['username' => 'author1']);
        $visitor = User::factory()->create(['username' => 'visitor1']);

        $response = $this->actingAs($visitor)->get(route('profile.show', $profileOwner->username));

        $response->assertOk();
        $response->assertSee('person_add');
        $response->assertDontSee('Edit Profile');
    }

    #[Test]
    public function authenticated_visitor_sees_following_button_when_already_following(): void
    {
        $profileOwner = User::factory()->create(['username' => 'author2']);
        $visitor = User::factory()->create(['username' => 'visitor2']);

        // Establish follow relationship — followers table has: id (uuid), user_id, follower_id, created_at
        \DB::table('followers')->insert([
            'id' => Str::uuid()->toString(),
            'user_id' => $profileOwner->id,
            'follower_id' => $visitor->id,
            'created_at' => now(),
        ]);

        $response = $this->actingAs($visitor)->get(route('profile.show', $profileOwner->username));

        $response->assertOk();
        $response->assertSee('person_remove');
    }

    #[Test]
    public function guest_sees_follow_button_that_links_to_login(): void
    {
        $user = User::factory()->create(['username' => 'author3']);

        $response = $this->get(route('profile.show', $user->username));

        $response->assertOk();
        $response->assertSee('person_add');
        $response->assertSee(route('login'));
    }

    // -------------------------------------------------------------------------
    // Posts listing
    // -------------------------------------------------------------------------

    #[Test]
    public function profile_page_lists_published_posts(): void
    {
        $user = User::factory()->create(['username' => 'author4']);

        $user->posts()->save(Post::factory()->published()->make([
            'title' => 'My Great AI Article',
        ]));

        $user->posts()->save(Post::factory()->draft()->make([
            'title' => 'Unpublished Draft',
        ]));

        $response = $this->get(route('profile.show', $user->username));

        $response->assertOk();
        $response->assertSee('My Great AI Article');
        $response->assertDontSee('Unpublished Draft');
    }

    #[Test]
    public function profile_page_shows_empty_state_when_user_has_no_posts(): void
    {
        $user = User::factory()->create(['username' => 'newbie']);

        $response = $this->get(route('profile.show', $user->username));

        $response->assertOk();
        $response->assertSee('No articles yet');
    }

    // -------------------------------------------------------------------------
    // Bio persistence
    // -------------------------------------------------------------------------

    #[Test]
    public function bio_column_is_stored_and_retrieved_correctly(): void
    {
        $user = User::factory()->create([
            'username' => 'biotest',
            'bio' => 'Software engineer passionate about writing.',
        ]);

        $this->assertDatabaseHas('users', [
            'username' => 'biotest',
            'bio' => 'Software engineer passionate about writing.',
        ]);

        $user->refresh();
        $this->assertSame('Software engineer passionate about writing.', $user->bio);
    }

    #[Test]
    public function bio_is_optional_and_defaults_to_null(): void
    {
        $user = User::factory()->create(['username' => 'nobio']);

        $this->assertNull($user->bio);

        $response = $this->get(route('profile.show', $user->username));

        $response->assertOk();
    }

    // -------------------------------------------------------------------------
    // Profile Editing
    // -------------------------------------------------------------------------

    #[Test]
    public function authenticated_user_can_view_profile_edit_page(): void
    {
        $user = User::factory()->create(['username' => 'edituser']);

        $response = $this->actingAs($user)->get(route('profile.edit'));

        $response->assertOk();
        $response->assertSee('Edit Profile');
    }

    #[Test]
    public function guest_cannot_view_profile_edit_page(): void
    {
        $response = $this->get(route('profile.edit'));

        $response->assertRedirect(route('login'));
    }

    #[Test]
    public function authenticated_user_can_update_profile_info(): void
    {
        $user = User::factory()->create([
            'name' => 'Old Name',
            'username' => 'olduser',
            'bio' => 'Old Bio',
        ]);

        $response = $this->actingAs($user)->put(route('profile.update'), [
            'name' => 'New Name',
            'username' => 'newuser',
            'bio' => 'New updated bio.',
        ]);

        $response->assertRedirect(route('profile.show', 'newuser'));

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'name' => 'New Name',
            'username' => 'newuser',
            'bio' => 'New updated bio.',
        ]);
    }
}
