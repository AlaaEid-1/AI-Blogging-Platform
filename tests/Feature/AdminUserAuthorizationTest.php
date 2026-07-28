<?php

namespace Tests\Feature;

use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class AdminUserAuthorizationTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function super_admin_can_access_admin_user_edit_page(): void
    {
        $superAdmin = User::factory()->create([
            'username' => 'superadmin',
            'type' => 'super-admin',
        ]);

        $targetUser = User::factory()->create([
            'username' => 'targetuser',
        ]);

        $response = $this->actingAs($superAdmin)
            ->get(route('users.edit', $targetUser));

        $response->assertRedirect(route('users.index'));
    }

    #[Test]
    public function admin_user_can_access_admin_user_edit_page(): void
    {
        $admin = User::factory()->create([
            'username' => 'adminuser',
            'type' => 'admin',
        ]);

        $targetUser = User::factory()->create([
            'username' => 'targetuser2',
        ]);

        $response = $this->actingAs($admin)
            ->get(route('users.edit', $targetUser));

        $response->assertRedirect(route('users.index'));
    }

    #[Test]
    public function user_with_users_manage_ability_role_can_access_admin_user_edit_page(): void
    {
        $role = Role::create([
            'name' => 'Manager',
            'abilities' => ['users.manage'],
        ]);

        $manager = User::factory()->create([
            'username' => 'manageruser',
            'type' => 'user',
        ]);
        $manager->roles()->attach($role->id);

        $targetUser = User::factory()->create([
            'username' => 'targetuser3',
        ]);

        $response = $this->actingAs($manager)
            ->get(route('users.edit', $targetUser));

        $response->assertRedirect(route('users.index'));
    }

    #[Test]
    public function normal_user_cannot_access_admin_user_edit_page(): void
    {
        $normalUser = User::factory()->create([
            'username' => 'normaluser',
            'type' => 'user',
        ]);

        $targetUser = User::factory()->create([
            'username' => 'targetuser4',
        ]);

        $response = $this->actingAs($normalUser)
            ->get(route('users.edit', $targetUser));

        $response->assertStatus(403);
    }

    #[Test]
    public function guest_cannot_access_admin_user_edit_page(): void
    {
        $targetUser = User::factory()->create([
            'username' => 'targetuser5',
        ]);

        $response = $this->get(route('users.edit', $targetUser));

        $response->assertRedirect(route('login'));
    }
}
