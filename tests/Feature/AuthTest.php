<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    public function test_login_successful()
    {
        $user = User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => bcrypt('password123'),
            'library_id' => '1234567890', // valor válido
        ]);

        $response = $this->postJson('/api/v1/login', [
            'email' => 'test@example.com',
            'password' => 'password123',
        ]);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'message',
                'data' => [
                    'id',
                    'name',
                    'email',
                    'role',
                    'permissions',
                    'token',
                ],
                'status',
            ]);
    }

    public function test_login_fails_with_invalid_credentials()
    {
        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => bcrypt('password123'),
            'library_id' => '1234567891',
        ]);

        $response = $this->postJson('/api/v1/login', [
            'email' => 'test@example.com',
            'password' => 'wrong-password',
        ]);

        $response->assertStatus(422)
            ->assertJson([
                'success' => false,
                'message' => 'Your password or email is incorrect',
            ]);
    }

    public function test_authenticated_user_can_access_protected_route()
    {
        $user = User::factory()->create([
            'password' => bcrypt('password123'),
            'library_id' => '1234567890',
        ]);

        $response = $this->postJson('/api/v1/login', [
            'email' => $user->email,
            'password' => 'password123',
        ]);

        $token = $response->json('data.token');

        $protectedResponse = $this->withHeaders([
            'Authorization' => "Bearer $token",
        ])->getJson('/api/v1/books'); // Ruta protegida de ejemplo

        $protectedResponse->assertStatus(200);
    }

    public function test_login_fails_with_missing_fields()
    {
        $response = $this->postJson('/api/v1/login', []);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['email', 'password']);
    }
}
