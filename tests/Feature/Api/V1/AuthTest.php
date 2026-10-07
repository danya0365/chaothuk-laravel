<?php

namespace Tests\Feature\Api\V1;

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

        // Registration assigns the MEMBER role, which only exists once the
        // permission and role seeders have run.
        $this->seed(\Database\Seeders\PermissionSeeder::class);
        $this->seed(\Database\Seeders\RoleSeeder::class);
    }

    public function test_a_visitor_can_register_and_receive_a_token(): void
    {
        $response = $this->postJson('/api/v1/auth/register', [
            'name' => 'สมชาย ใจดี',
            'email' => 'somchai@example.com',
            'password' => 'secret123',
            'password_confirmation' => 'secret123',
            'first_name' => 'สมชาย',
            'last_name' => 'ใจดี',
        ]);

        $response->assertCreated();
        $response->assertJsonStructure([
            'data' => [
                'user' => ['id', 'name', 'email', 'permissions'],
                'token',
            ],
        ]);

        $this->assertDatabaseHas('users', ['email' => 'somchai@example.com']);

        $user = User::where('email', 'somchai@example.com')->firstOrFail();
        $this->assertTrue(Hash::check('secret123', $user->password));
    }

    public function test_a_registered_user_can_log_in(): void
    {
        $user = User::factory()->create(['password' => Hash::make('secret123')]);

        $response = $this->postJson('/api/v1/auth/login', [
            'email' => $user->email,
            'password' => 'secret123',
        ]);

        $response->assertOk();
        $response->assertJsonPath('data.user.id', $user->id);
        $response->assertJsonStructure(['data' => ['user', 'token']]);
    }

    public function test_login_rejects_a_wrong_password(): void
    {
        $user = User::factory()->create(['password' => Hash::make('secret123')]);

        $this->postJson('/api/v1/auth/login', [
            'email' => $user->email,
            'password' => 'wrong-password',
        ])->assertUnprocessable();
    }

    public function test_me_requires_authentication(): void
    {
        $this->getJson('/api/v1/auth/me')->assertUnauthorized();
    }

    public function test_me_returns_the_signed_in_user_with_permissions(): void
    {
        $user = User::factory()->create();
        $user->roles()->sync([\App\Enums\Role::MEMBER->value]);

        $response = $this->actingAs($user, 'sanctum')->getJson('/api/v1/auth/me');

        $response->assertOk();
        $response->assertJsonPath('data.id', $user->id);
        $response->assertJsonStructure(['data' => ['id', 'email', 'permissions']]);
    }

    public function test_logout_revokes_the_current_token(): void
    {
        $user = User::factory()->create();
        $token = $user->createToken('mobile-app')->plainTextToken;

        $response = $this->withHeader('Authorization', "Bearer {$token}")
            ->postJson('/api/v1/auth/logout');

        $response->assertOk();
        $this->assertDatabaseCount('personal_access_tokens', 0);
    }

    public function test_registration_rejects_a_duplicate_email(): void
    {
        $user = User::factory()->create();

        $this->postJson('/api/v1/auth/register', [
            'name' => 'ทดสอบ',
            'email' => $user->email,
            'password' => 'secret123',
            'password_confirmation' => 'secret123',
            'first_name' => 'ทดสอบ',
            'last_name' => 'ระบบ',
        ])->assertUnprocessable();
    }
}
