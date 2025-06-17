<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class UserTest extends TestCase
{
    use RefreshDatabase;

    protected function authenticate()
    {
        $user = User::factory()->create([
            'password' => bcrypt('password123'),
            'library_id' => '1234567890',
        ]);

        $token = $this->postJson('/api/v1/login', [
            'email' => $user->email,
            'password' => 'password123',
        ])->json('data.token');

        return [
            'Authorization' => "Bearer $token",
            'Accept' => 'application/json'
        ];
    }

    public function test_can_list_users()
    {
        $headers = $this->authenticate();

        $response = $this->getJson('/api/v1/users', $headers);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'message',
                'data' => ['data'],
                'status'
            ]);
    }

    public function test_can_create_user()
    {
        $headers = $this->authenticate();
        Role::create(['name' => 'admin']);

        $data = [
            'name' => 'New User',
            'email' => 'newuser@example.com',
            'library_id' => '9999999999',
            'password' => 'secret123',
            'role' => 'admin',
        ];

        $response = $this->postJson('/api/v1/users', $data, $headers);

        $response->assertStatus(201)
            ->assertJson([
                'success' => true,
                'message' => 'User created'
            ]);

        $this->assertDatabaseHas('users', [
            'email' => 'newuser@example.com',
        ]);
    }

    public function test_can_update_user()
    {
        $headers = $this->authenticate();
        Role::create(['name' => 'admin']);

        $user = User::factory()->create([
            'library_id' => '8888888888'
        ]);
        $user->assignRole('admin');

        $data = [
            'name' => 'Updated Name',
            'email' => 'updated@example.com',
            'library_id' => '8888888888',
            'role' => 'admin',
        ];

        $response = $this->putJson("/api/v1/users/{$user->id}", $data, $headers);

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'message' => 'User updated',
                'data' => [
                    'name' => 'Updated Name',
                    'email' => 'updated@example.com',
                ]
            ]);
    }

    public function test_can_delete_user()
    {
        $headers = $this->authenticate();

        $user = User::factory()->create();

        $response = $this->deleteJson("/api/v1/users/{$user->id}", [], $headers);

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'message' => 'User deleted',
            ]);

        $this->assertDatabaseMissing('users', [
            'id' => $user->id
        ]);
    }
}
