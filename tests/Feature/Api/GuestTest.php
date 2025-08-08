<?php

namespace Tests\Feature\Api;

use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class GuestTest extends TestCase
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

    public function test_can_create_guest_user(): void
    {
        $response = $this->postJson('/api/guest/create');

        $response->assertStatus(201)
            ->assertJsonStructure([
                'success',
                'message',
                'user' => ['id', 'name', 'is_guest'],
                'token',
                'guest_token'
            ])
            ->assertJson([
                'success' => true,
                'message' => 'Guest user created successfully',
            ]);

        $responseData = $response->json();
        
        $this->assertDatabaseHas('users', [
            'id' => $responseData['user']['id'],
            'is_guest' => true,
            'guest_token' => $responseData['guest_token'],
        ]);

        $user = User::find($responseData['user']['id']);
        $this->assertEquals('guest', $user->role->name);
        $this->assertNotNull($user->guest_token);
    }

    public function test_can_authenticate_guest_with_valid_token(): void
    {
        $guestUser = User::factory()->guest()->create();

        $response = $this->postJson('/api/guest/auth', [
            'guest_token' => $guestUser->guest_token,
        ]);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'message',
                'user' => ['id', 'name', 'is_guest'],
                'token'
            ])
            ->assertJson([
                'success' => true,
                'message' => 'Guest authenticated successfully',
            ]);
    }

    public function test_cannot_authenticate_guest_with_invalid_token(): void
    {
        $response = $this->postJson('/api/guest/auth', [
            'guest_token' => 'invalid-token',
        ]);

        $response->assertStatus(401)
            ->assertJson([
                'success' => false,
                'message' => 'Invalid guest token',
            ]);
    }

    public function test_guest_authentication_requires_token(): void
    {
        $response = $this->postJson('/api/guest/auth', []);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['guest_token']);
    }

    public function test_authenticated_guest_can_get_data(): void
    {
        $guestUser = User::factory()->guest()->create([
            'progress_data' => ['level' => 1, 'score' => 100],
        ]);
        Sanctum::actingAs($guestUser);

        $response = $this->getJson('/api/guest/data');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'user' => ['id', 'name', 'is_guest'],
                'progress_data'
            ])
            ->assertJson([
                'success' => true,
                'progress_data' => ['level' => 1, 'score' => 100],
            ]);
    }

    public function test_non_guest_user_cannot_get_guest_data(): void
    {
        $user = User::factory()->customer()->create();
        Sanctum::actingAs($user);

        $response = $this->getJson('/api/guest/data');

        $response->assertStatus(401)
            ->assertJson([
                'success' => false,
                'message' => 'Invalid guest user',
            ]);
    }

    public function test_authenticated_guest_can_update_data(): void
    {
        $guestUser = User::factory()->guest()->create();
        Sanctum::actingAs($guestUser);

        $updateData = [
            'name' => 'Custom Guest Name',
            'progress_data' => ['level' => 2, 'score' => 250, 'items' => ['sword', 'shield']],
        ];

        $response = $this->putJson('/api/guest/data', $updateData);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'message',
                'user' => ['id', 'name', 'is_guest']
            ])
            ->assertJson([
                'success' => true,
                'message' => 'Guest data updated successfully',
            ]);

        $guestUser->refresh();
        $this->assertEquals('Custom Guest Name', $guestUser->name);
        $this->assertEquals(['level' => 2, 'score' => 250, 'items' => ['sword', 'shield']], $guestUser->progress_data);
    }

    public function test_non_guest_user_cannot_update_guest_data(): void
    {
        $user = User::factory()->customer()->create();
        Sanctum::actingAs($user);

        $response = $this->putJson('/api/guest/data', [
            'name' => 'Test Name',
            'progress_data' => ['level' => 1],
        ]);

        $response->assertStatus(401)
            ->assertJson([
                'success' => false,
                'message' => 'Invalid guest user',
            ]);
    }

    public function test_authenticated_guest_can_delete_account(): void
    {
        $guestUser = User::factory()->guest()->create();
        $guestUserId = $guestUser->id;
        Sanctum::actingAs($guestUser);

        $response = $this->deleteJson('/api/guest');

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'message' => 'Guest user deleted successfully',
            ]);

        $this->assertDatabaseMissing('users', ['id' => $guestUserId]);
        $this->assertDatabaseMissing('personal_access_tokens', [
            'tokenable_id' => $guestUserId,
        ]);
    }

    public function test_non_guest_user_cannot_delete_guest_account(): void
    {
        $user = User::factory()->customer()->create();
        Sanctum::actingAs($user);

        $response = $this->deleteJson('/api/guest');

        $response->assertStatus(401)
            ->assertJson([
                'success' => false,
                'message' => 'Invalid guest user',
            ]);

        $this->assertDatabaseHas('users', ['id' => $user->id]);
    }

    public function test_unauthenticated_user_cannot_access_guest_endpoints(): void
    {
        $endpoints = [
            ['method' => 'get', 'uri' => '/api/guest/data'],
            ['method' => 'put', 'uri' => '/api/guest/data'],
            ['method' => 'delete', 'uri' => '/api/guest'],
        ];

        foreach ($endpoints as $endpoint) {
            $response = $this->{$endpoint['method'] . 'Json'}($endpoint['uri']);
            $response->assertStatus(401);
        }
    }
}