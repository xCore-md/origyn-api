<?php

namespace Tests\Feature\Api;

use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class AuthTest extends TestCase
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

    public function test_user_can_register(): void
    {
        $userData = [
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ];

        $response = $this->postJson('/api/register', $userData);

        $response->assertStatus(201)
            ->assertJsonStructure([
                'success',
                'message',
                'user' => ['id', 'name', 'email', 'is_guest'],
                'token'
            ])
            ->assertJson([
                'success' => true,
                'message' => 'User registered successfully',
            ]);

        $this->assertDatabaseHas('users', [
            'email' => 'john@example.com',
            'is_guest' => false,
        ]);

        $user = User::where('email', 'john@example.com')->first();
        $this->assertNotNull($user->role_id);
        $this->assertEquals('customer', $user->role->name);
    }

    public function test_register_validation_fails(): void
    {
        $response = $this->postJson('/api/register', [
            'name' => '',
            'email' => 'invalid-email',
            'password' => '123',
        ]);

        $response->assertStatus(422)
            ->assertJsonStructure([
                'success',
                'message',
                'errors' => ['name', 'email', 'password']
            ])
            ->assertJson(['success' => false]);
    }

    public function test_user_can_login(): void
    {
        $user = User::factory()->customer()->create([
            'email' => 'john@example.com',
            'password' => Hash::make('password123'),
        ]);

        $response = $this->postJson('/api/login', [
            'email' => 'john@example.com',
            'password' => 'password123',
        ]);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'message',
                'user' => ['id', 'name', 'email'],
                'token'
            ])
            ->assertJson([
                'success' => true,
                'message' => 'Login successful',
            ]);
    }

    public function test_login_fails_with_wrong_credentials(): void
    {
        $user = User::factory()->customer()->create([
            'email' => 'john@example.com',
            'password' => Hash::make('password123'),
        ]);

        $response = $this->postJson('/api/login', [
            'email' => 'john@example.com',
            'password' => 'wrong-password',
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['email']);
    }

    public function test_authenticated_user_can_get_profile(): void
    {
        $user = User::factory()->customer()->create();
        Sanctum::actingAs($user);

        $response = $this->getJson('/api/user');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'user' => ['id', 'name', 'email', 'is_guest']
            ])
            ->assertJson([
                'success' => true,
                'user' => [
                    'id' => $user->id,
                    'email' => $user->email,
                ]
            ]);
    }

    public function test_unauthenticated_user_cannot_get_profile(): void
    {
        $response = $this->getJson('/api/user');

        $response->assertStatus(401);
    }

    public function test_authenticated_user_can_logout(): void
    {
        $user = User::factory()->customer()->create();
        Sanctum::actingAs($user);

        $response = $this->postJson('/api/logout');

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'message' => 'Logged out successfully',
            ]);

        // Verify token was deleted
        $this->assertDatabaseMissing('personal_access_tokens', [
            'tokenable_id' => $user->id,
        ]);
    }

    public function test_guest_user_can_convert_to_registered(): void
    {
        $guestUser = User::factory()->guest()->create();
        Sanctum::actingAs($guestUser);

        $conversionData = [
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ];

        $response = $this->postJson('/api/convert-guest', $conversionData);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'message',
                'user' => ['id', 'name', 'email', 'is_guest']
            ])
            ->assertJson([
                'success' => true,
                'message' => 'Guest account converted to registered user successfully',
            ]);

        $guestUser->refresh();
        $this->assertFalse($guestUser->is_guest);
        $this->assertEquals('john@example.com', $guestUser->email);
        $this->assertEquals('customer', $guestUser->role->name);
        $this->assertNull($guestUser->guest_token);
    }

    public function test_registered_user_cannot_convert_to_registered(): void
    {
        $user = User::factory()->customer()->create();
        Sanctum::actingAs($user);

        $conversionData = [
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ];

        $response = $this->postJson('/api/convert-guest', $conversionData);

        $response->assertStatus(400)
            ->assertJson([
                'success' => false,
                'message' => 'User is not a guest',
            ]);
    }
}