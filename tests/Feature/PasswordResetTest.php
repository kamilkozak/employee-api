<?php

namespace Tests\Feature;

use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Models\User;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class PasswordResetTest extends TestCase
{
    use LazilyRefreshDatabase;

    public function test_reset_password_link_can_be_requested(): void
    {
        Notification::fake();
        $user = User::factory()->active()->create();

        $response = $this->postJson(action(ForgotPasswordController::class), [
            'email' => $user->email,
        ]);

        Notification::assertSentTo($user, ResetPassword::class);
    }

    public function test_password_can_be_reset_with_valid_token(): void
    {
        Notification::fake();

        $user = User::factory()->active()->create();

        $response = $this->postJson(action(ForgotPasswordController::class), [
            'email' => $user->email,
        ]);

        Notification::assertSentTo(
            $user,
            ResetPassword::class,
            function (ResetPassword $notification) use ($user) {
                $response = $this->postJson(action(ResetPasswordController::class), [
                    'token' => $notification->token,
                    'email' => $user->email,
                    'password' => 'password',
                    'password_confirmation' => 'password',
                ]);

                $response->assertJsonMissingValidationErrors()
                    ->assertStatus(Response::HTTP_OK);

                return true;
            });
    }
}
