<?php

namespace Tests\Feature;

use App\Models\User;
use Database\Seeders\ShopSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthApiTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(ShopSeeder::class);
    }

    public function test_admin_can_login_and_receive_token(): void
    {
        $response = $this->postJson('/api/auth/login', [
            'email' => 'admin@example.com',
            'password' => 'Admin123!',
        ]);

        $response->assertOk()
            ->assertJsonStructure(['token', 'user' => ['email', 'role']])
            ->assertJsonPath('user.role', 'admin');
    }

    public function test_registration_creates_customer(): void
    {
        $response = $this->postJson('/api/auth/register', [
            'name' => 'New User',
            'email' => 'newuser@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertCreated()
            ->assertJsonPath('user.email', 'newuser@example.com')
            ->assertJsonPath('user.role', 'customer');

        $this->assertDatabaseHas('users', ['email' => 'newuser@example.com', 'role' => 'customer']);
    }
}

