<?php

namespace Tests\Feature;

use App\Models\Department;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        
        $this->artisan('db:seed', ['--class' => 'Database\\Seeders\\RoleSeeder']);
        $this->artisan('db:seed', ['--class' => 'Database\\Seeders\\DepartmentSeeder']);
    }

    public function test_user_can_register(): void
    {
        $response = $this->postJson('/api/v1/register', [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'department_id' => 1,
        ]);

        $response->assertStatus(201)
            ->assertJsonStructure([
                'message',
                'user' => ['id', 'name', 'email', 'role'],
                'token',
            ]);

        $this->assertDatabaseHas('users', [
            'email' => 'test@example.com',
        ]);
    }

    public function test_user_can_login(): void
    {
        $user = User::factory()->create([
            'email' => 'user@example.com',
            'password' => Hash::make('password'),
            'department_id' => 1,
        ]);
        $user->assignRole('employee');

        $response = $this->postJson('/api/v1/login', [
            'email' => 'user@example.com',
            'password' => 'password',
        ]);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'message',
                'user',
                'token',
            ]);
    }

    public function test_user_can_logout(): void
    {
        $user = User::factory()->create([
            'department_id' => 1,
        ]);
        $user->assignRole('employee');
        $token = $user->createToken('auth_token')->plainTextToken;

        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
            ->postJson('/api/v1/logout');

        $response->assertStatus(200)
            ->assertJson(['message' => 'Logged out successfully']);
    }
}
