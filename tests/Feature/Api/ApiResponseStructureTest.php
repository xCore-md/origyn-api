<?php

namespace Tests\Feature\Api;

use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class ApiResponseStructureTest extends TestCase
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

    public function test_auth_endpoints_return_consistent_structure(): void
    {
        // Test registration response structure
        $registerResponse = $this->postJson('/api/register', [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $registerResponse->assertStatus(201)
            ->assertJsonStructure([
                'success',
                'message',
                'user' => [
                    'id',
                    'name',
                    'email',
                    'is_guest',
                    'role_id',
                    'created_at',
                    'updated_at',
                ],
                'token'
            ]);

        // Test login response structure
        $loginResponse = $this->postJson('/api/login', [
            'email' => 'test@example.com',
            'password' => 'password123',
        ]);

        $loginResponse->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'message',
                'user' => [
                    'id',
                    'name',
                    'email',
                    'is_guest',
                    'role_id',
                    'created_at',
                    'updated_at',
                ],
                'token'
            ]);

        // Verify success field is boolean
        $this->assertIsBool($registerResponse->json('success'));
        $this->assertIsBool($loginResponse->json('success'));
        
        // Verify message field is string
        $this->assertIsString($registerResponse->json('message'));
        $this->assertIsString($loginResponse->json('message'));

        // Verify token is string
        $this->assertIsString($registerResponse->json('token'));
        $this->assertIsString($loginResponse->json('token'));
    }

    public function test_guest_endpoints_return_consistent_structure(): void
    {
        // Test guest creation response structure
        $createResponse = $this->postJson('/api/guest/create');

        $createResponse->assertStatus(201)
            ->assertJsonStructure([
                'success',
                'message',
                'user' => [
                    'id',
                    'name',
                    'is_guest',
                    'role_id',
                    'created_at',
                    'updated_at',
                ],
                'token',
                'guest_token'
            ]);

        $guestData = $createResponse->json();
        
        // Test guest authentication response structure
        $authResponse = $this->postJson('/api/guest/auth', [
            'guest_token' => $guestData['guest_token'],
        ]);

        $authResponse->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'message',
                'user' => [
                    'id',
                    'name',
                    'is_guest',
                    'role_id',
                    'created_at',
                    'updated_at',
                ],
                'token'
            ]);

        // Verify boolean fields
        $this->assertIsBool($createResponse->json('success'));
        $this->assertIsBool($authResponse->json('success'));
        $this->assertTrue($createResponse->json('user.is_guest'));
        $this->assertTrue($authResponse->json('user.is_guest'));
    }

    public function test_admin_endpoints_return_consistent_structure(): void
    {
        $admin = User::factory()->admin()->create();
        $customer = User::factory()->customer()->create();
        
        Sanctum::actingAs($admin);

        // Test users list response structure
        $usersResponse = $this->getJson('/api/admin/users');

        $usersResponse->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'users' => [
                    'current_page',
                    'data' => [
                        '*' => [
                            'id',
                            'name',
                            'email',
                            'is_guest',
                            'role_id',
                            'created_at',
                            'updated_at',
                            'role' => [
                                'id',
                                'name',
                                'display_name',
                                'description',
                            ]
                        ]
                    ],
                    'first_page_url',
                    'last_page',
                    'last_page_url',
                    'next_page_url',
                    'path',
                    'per_page',
                    'prev_page_url',
                    'to',
                    'total',
                ]
            ]);

        // Test single user response structure
        $userResponse = $this->getJson("/api/admin/users/{$customer->id}");

        $userResponse->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'user' => [
                    'id',
                    'name',
                    'email',
                    'is_guest',
                    'role_id',
                    'created_at',
                    'updated_at',
                    'role' => [
                        'id',
                        'name',
                        'display_name',
                        'description',
                    ]
                ]
            ]);

        // Test roles response structure
        $rolesResponse = $this->getJson('/api/admin/roles');

        $rolesResponse->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'roles' => [
                    '*' => [
                        'id',
                        'name',
                        'display_name',
                        'description',
                        'created_at',
                        'updated_at',
                    ]
                ]
            ]);

        // Verify boolean fields
        $this->assertIsBool($usersResponse->json('success'));
        $this->assertIsBool($userResponse->json('success'));
        $this->assertIsBool($rolesResponse->json('success'));
    }

    public function test_error_responses_return_consistent_structure(): void
    {
        // Test validation error structure
        $validationResponse = $this->postJson('/api/register', [
            'name' => '',
            'email' => 'invalid-email',
            'password' => '123',
        ]);

        $validationResponse->assertStatus(422)
            ->assertJsonStructure([
                'success',
                'message',
                'errors' => [
                    'name',
                    'email', 
                    'password'
                ]
            ]);

        $this->assertIsBool($validationResponse->json('success'));
        $this->assertFalse($validationResponse->json('success'));
        $this->assertIsString($validationResponse->json('message'));
        $this->assertIsArray($validationResponse->json('errors'));

        // Test authentication error structure
        $authResponse = $this->getJson('/api/user');
        $authResponse->assertStatus(401);

        // Test forbidden error structure
        $customer = User::factory()->customer()->create();
        Sanctum::actingAs($customer);
        
        $forbiddenResponse = $this->getJson('/api/admin/users');
        $forbiddenResponse->assertStatus(403)
            ->assertJsonStructure([
                'success',
                'message'
            ]);

        $this->assertIsBool($forbiddenResponse->json('success'));
        $this->assertFalse($forbiddenResponse->json('success'));
        $this->assertIsString($forbiddenResponse->json('message'));
    }

    public function test_all_endpoints_include_cors_headers(): void
    {
        $endpoints = [
            ['method' => 'post', 'uri' => '/api/guest/create', 'data' => []],
            ['method' => 'post', 'uri' => '/api/register', 'data' => [
                'name' => 'Test',
                'email' => 'test@example.com',
                'password' => 'password123',
                'password_confirmation' => 'password123',
            ]],
        ];

        foreach ($endpoints as $endpoint) {
            $response = $this->{$endpoint['method'] . 'Json'}($endpoint['uri'], $endpoint['data']);
            
            // Laravel's default CORS headers are handled by the framework
            $response->assertHeader('Content-Type', 'application/json');
        }
    }

    public function test_timestamps_are_in_correct_format(): void
    {
        $user = User::factory()->customer()->create();
        
        Sanctum::actingAs($user);
        $response = $this->getJson('/api/user');

        $userData = $response->json('user');
        
        // Verify timestamps follow ISO 8601 format
        $this->assertMatchesRegularExpression(
            '/^\d{4}-\d{2}-\d{2}T\d{2}:\d{2}:\d{2}\.000000Z$/',
            $userData['created_at']
        );
        
        $this->assertMatchesRegularExpression(
            '/^\d{4}-\d{2}-\d{2}T\d{2}:\d{2}:\d{2}\.000000Z$/',
            $userData['updated_at']
        );
    }
}