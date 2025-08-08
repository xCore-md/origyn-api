<?php

namespace Tests\Feature\Api;

use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class AdminTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Create roles
        Role::factory()->admin()->create();
        Role::factory()->customer()->create();
        Role::factory()->guest()->create();
    }

    public function test_admin_can_list_users(): void
    {
        $admin = User::factory()->admin()->create();
        User::factory()->customer()->count(3)->create();
        User::factory()->guest()->count(2)->create();
        
        Sanctum::actingAs($admin);

        $response = $this->getJson('/api/admin/users');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'users' => [
                    'data' => [
                        '*' => ['id', 'name', 'email', 'is_guest', 'role']
                    ]
                ]
            ])
            ->assertJson(['success' => true]);
    }

    public function test_admin_can_filter_users_by_role(): void
    {
        $admin = User::factory()->admin()->create();
        User::factory()->customer()->count(2)->create();
        User::factory()->guest()->count(3)->create();
        
        Sanctum::actingAs($admin);

        $response = $this->getJson('/api/admin/users?role=customer');

        $response->assertStatus(200)
            ->assertJson(['success' => true]);
        
        $users = $response->json('users.data');
        foreach ($users as $user) {
            $this->assertEquals('customer', $user['role']['name']);
        }
    }

    public function test_admin_can_filter_users_by_guest_status(): void
    {
        $admin = User::factory()->admin()->create();
        User::factory()->customer()->count(2)->create();
        User::factory()->guest()->count(3)->create();
        
        Sanctum::actingAs($admin);

        $response = $this->getJson('/api/admin/users?is_guest=true');

        $response->assertStatus(200)
            ->assertJson(['success' => true]);
        
        $users = $response->json('users.data');
        foreach ($users as $user) {
            $this->assertTrue($user['is_guest']);
        }
    }

    public function test_admin_can_view_single_user(): void
    {
        $admin = User::factory()->admin()->create();
        $user = User::factory()->customer()->create();
        
        Sanctum::actingAs($admin);

        $response = $this->getJson("/api/admin/users/{$user->id}");

        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'user' => ['id', 'name', 'email', 'is_guest', 'role']
            ])
            ->assertJson([
                'success' => true,
                'user' => ['id' => $user->id]
            ]);
    }

    public function test_admin_can_update_user_role(): void
    {
        $admin = User::factory()->admin()->create();
        $user = User::factory()->customer()->create();
        $guestRole = Role::where('name', 'guest')->first();
        
        Sanctum::actingAs($admin);

        $response = $this->patchJson("/api/admin/users/{$user->id}/role", [
            'role_id' => $guestRole->id,
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'message' => 'User role updated successfully',
            ]);

        $user->refresh();
        $this->assertEquals($guestRole->id, $user->role_id);
    }

    public function test_admin_cannot_update_user_role_with_invalid_role(): void
    {
        $admin = User::factory()->admin()->create();
        $user = User::factory()->customer()->create();
        
        Sanctum::actingAs($admin);

        $response = $this->patchJson("/api/admin/users/{$user->id}/role", [
            'role_id' => 999,
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['role_id']);
    }

    public function test_admin_can_delete_user(): void
    {
        $admin = User::factory()->admin()->create();
        $user = User::factory()->customer()->create();
        $userId = $user->id;
        
        Sanctum::actingAs($admin);

        $response = $this->deleteJson("/api/admin/users/{$user->id}");

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'message' => 'User deleted successfully',
            ]);

        $this->assertDatabaseMissing('users', ['id' => $userId]);
    }

    public function test_admin_cannot_delete_another_admin(): void
    {
        $admin1 = User::factory()->admin()->create();
        $admin2 = User::factory()->admin()->create();
        
        Sanctum::actingAs($admin1);

        $response = $this->deleteJson("/api/admin/users/{$admin2->id}");

        $response->assertStatus(403)
            ->assertJson([
                'success' => false,
                'message' => 'Cannot delete admin users',
            ]);

        $this->assertDatabaseHas('users', ['id' => $admin2->id]);
    }

    public function test_admin_can_list_roles(): void
    {
        $admin = User::factory()->admin()->create();
        
        Sanctum::actingAs($admin);

        $response = $this->getJson('/api/admin/roles');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'roles' => [
                    '*' => ['id', 'name', 'display_name', 'description']
                ]
            ])
            ->assertJson(['success' => true]);
        
        $roles = $response->json('roles');
        $roleNames = array_column($roles, 'name');
        
        $this->assertContains('admin', $roleNames);
        $this->assertContains('customer', $roleNames);
        $this->assertContains('guest', $roleNames);
    }

    public function test_customer_cannot_access_admin_endpoints(): void
    {
        $customer = User::factory()->customer()->create();
        $targetUser = User::factory()->customer()->create();
        
        Sanctum::actingAs($customer);

        $endpoints = [
            ['method' => 'get', 'uri' => '/api/admin/users'],
            ['method' => 'get', 'uri' => "/api/admin/users/{$targetUser->id}"],
            ['method' => 'patch', 'uri' => "/api/admin/users/{$targetUser->id}/role", 'data' => ['role_id' => 1]],
            ['method' => 'delete', 'uri' => "/api/admin/users/{$targetUser->id}"],
            ['method' => 'get', 'uri' => '/api/admin/roles'],
        ];

        foreach ($endpoints as $endpoint) {
            $data = $endpoint['data'] ?? [];
            $response = $this->{$endpoint['method'] . 'Json'}($endpoint['uri'], $data);
            $response->assertStatus(403)
                ->assertJson([
                    'success' => false,
                    'message' => 'Insufficient permissions',
                ]);
        }
    }

    public function test_guest_cannot_access_admin_endpoints(): void
    {
        $guest = User::factory()->guest()->create();
        $targetUser = User::factory()->customer()->create();
        
        Sanctum::actingAs($guest);

        $endpoints = [
            ['method' => 'get', 'uri' => '/api/admin/users'],
            ['method' => 'get', 'uri' => "/api/admin/users/{$targetUser->id}"],
            ['method' => 'patch', 'uri' => "/api/admin/users/{$targetUser->id}/role", 'data' => ['role_id' => 1]],
            ['method' => 'delete', 'uri' => "/api/admin/users/{$targetUser->id}"],
            ['method' => 'get', 'uri' => '/api/admin/roles'],
        ];

        foreach ($endpoints as $endpoint) {
            $data = $endpoint['data'] ?? [];
            $response = $this->{$endpoint['method'] . 'Json'}($endpoint['uri'], $data);
            $response->assertStatus(403)
                ->assertJson([
                    'success' => false,
                    'message' => 'Insufficient permissions',
                ]);
        }
    }

    public function test_unauthenticated_user_cannot_access_admin_endpoints(): void
    {
        $targetUser = User::factory()->customer()->create();

        $endpoints = [
            ['method' => 'get', 'uri' => '/api/admin/users'],
            ['method' => 'get', 'uri' => "/api/admin/users/{$targetUser->id}"],
            ['method' => 'patch', 'uri' => "/api/admin/users/{$targetUser->id}/role", 'data' => ['role_id' => 1]],
            ['method' => 'delete', 'uri' => "/api/admin/users/{$targetUser->id}"],
            ['method' => 'get', 'uri' => '/api/admin/roles'],
        ];

        foreach ($endpoints as $endpoint) {
            $data = $endpoint['data'] ?? [];
            $response = $this->{$endpoint['method'] . 'Json'}($endpoint['uri'], $data);
            $response->assertStatus(401);
        }
    }
}