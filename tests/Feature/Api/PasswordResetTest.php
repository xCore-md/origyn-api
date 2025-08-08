<?php

namespace Tests\Feature\Api;

use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Tests\TestCase;

class PasswordResetTest extends TestCase
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

    public function test_can_send_password_reset_link(): void
    {
        $user = User::factory()->customer()->create([
            'email' => 'test@example.com',
        ]);

        $response = $this->postJson('/api/password/email', [
            'email' => 'test@example.com',
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'message' => 'Password reset link sent to your email',
            ]);
    }

    public function test_cannot_send_password_reset_link_for_non_existent_email(): void
    {
        $response = $this->postJson('/api/password/email', [
            'email' => 'nonexistent@example.com',
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['email']);
    }

    public function test_cannot_send_password_reset_link_for_guest_user(): void
    {
        // Manually create a guest user with email for testing
        $guestRole = Role::where('name', 'guest')->first();
        $guestWithEmail = User::factory()->create([
            'email' => 'guest@example.com',
            'is_guest' => true,
            'role_id' => $guestRole->id,
        ]);

        $response = $this->postJson('/api/password/email', [
            'email' => 'guest@example.com',
        ]);

        $response->assertStatus(400)
            ->assertJson([
                'success' => false,
                'message' => 'Cannot reset password for guest users',
            ]);
    }

    public function test_password_reset_email_validation(): void
    {
        $response = $this->postJson('/api/password/email', [
            'email' => 'invalid-email',
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['email']);
    }

    public function test_password_reset_requires_email(): void
    {
        $response = $this->postJson('/api/password/email', []);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['email']);
    }

    public function test_can_reset_password_with_valid_token(): void
    {
        $user = User::factory()->customer()->create([
            'email' => 'test@example.com',
            'password' => Hash::make('old-password'),
        ]);

        // Generate a reset token
        $token = Password::broker()->createToken($user);

        $response = $this->postJson('/api/password/reset', [
            'email' => 'test@example.com',
            'password' => 'new-password123',
            'password_confirmation' => 'new-password123',
            'token' => $token,
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'message' => 'Password has been reset successfully',
            ]);

        // Verify password was changed
        $user->refresh();
        $this->assertTrue(Hash::check('new-password123', $user->password));

        // Verify all tokens were deleted
        $this->assertDatabaseMissing('personal_access_tokens', [
            'tokenable_id' => $user->id,
        ]);
    }

    public function test_cannot_reset_password_with_invalid_token(): void
    {
        $user = User::factory()->customer()->create([
            'email' => 'test@example.com',
        ]);

        $response = $this->postJson('/api/password/reset', [
            'email' => 'test@example.com',
            'password' => 'new-password123',
            'password_confirmation' => 'new-password123',
            'token' => 'invalid-token',
        ]);

        $response->assertStatus(400)
            ->assertJson([
                'success' => false,
                'message' => 'Invalid or expired reset token',
            ]);
    }

    public function test_password_reset_validation_rules(): void
    {
        $response = $this->postJson('/api/password/reset', [
            'email' => 'invalid-email',
            'password' => '123',
            'password_confirmation' => '456',
            'token' => '',
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['email', 'password', 'token']);
    }

    public function test_password_reset_requires_confirmation(): void
    {
        $user = User::factory()->customer()->create([
            'email' => 'test@example.com',
        ]);
        
        $token = Password::broker()->createToken($user);

        $response = $this->postJson('/api/password/reset', [
            'email' => 'test@example.com',
            'password' => 'new-password123',
            'password_confirmation' => 'different-password',
            'token' => $token,
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['password']);
    }

    public function test_password_reset_minimum_length(): void
    {
        $user = User::factory()->customer()->create([
            'email' => 'test@example.com',
        ]);
        
        $token = Password::broker()->createToken($user);

        $response = $this->postJson('/api/password/reset', [
            'email' => 'test@example.com',
            'password' => '123',
            'password_confirmation' => '123',
            'token' => $token,
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['password']);
    }

    public function test_password_reset_requires_all_fields(): void
    {
        $response = $this->postJson('/api/password/reset', []);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['token', 'email', 'password']);
    }
}