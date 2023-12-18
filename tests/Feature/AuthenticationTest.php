<?php

namespace Tests\Feature;

use App\Http\Controllers\Auth\LoginController;
use App\Models\User;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

class AuthenticationTest extends TestCase
{
    use LazilyRefreshDatabase;

    public function test_active_user_can_authenticate(): void
    {
        $user = User::factory()->active()->create();

        $response = $this->postJson(action(LoginController::class), [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $response->assertSuccessful();
        $response->assertJson(fn (AssertableJson $json) => $json->has('access_token'));
        $this->assertCount(1, $user->fresh()->tokens);
    }

    public function test_inactive_user_can_not_authenticate(): void
    {
        $user = User::factory()->active(false)->create();

        $response = $this->postJson(action(LoginController::class), [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $response->assertJsonValidationErrors(['email']);
        $this->assertCount(0, $user->fresh()->tokens);
    }

    public function test_user_can_not_authenticate_with_invalid_password(): void
    {
        $user = User::factory()->active()->create();

        $response = $this->postJson(action(LoginController::class), [
            'email' => $user->email,
            'password' => 'wrong-password',
        ]);

        $response->assertJsonValidationErrors(['email']);
        $this->assertCount(0, $user->fresh()->tokens);
    }
}
