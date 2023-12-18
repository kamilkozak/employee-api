<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\ResetPasswordRequest;
use App\Models\User;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

/**
 * @group Auth
 */
class ResetPasswordController extends Controller
{
    /**
     * Reset Password
     *
     * @bodyParam token string Example: e2a877917ecde7664093b16d525d0787c0ff2eae33a7a5372fe9f3e7364e6680
     * @bodyParam email string Example: test@example.com
     * @bodyParam password string Example: password
     * @bodyParam password_confirmation string Example: password
     *
     * @response 200 {
     *      "message": "Your password has been reset."
     *  }
     *
     * @unauthenticated
     */
    public function __invoke(ResetPasswordRequest $request)
    {
        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function (User $user, string $password) {
                $user->forceFill([
                    'password' => Hash::make($password),
                ])->setRememberToken(Str::random(60));

                $user->save();

                event(new PasswordReset($user));
            }
        );

        return $status === Password::PASSWORD_RESET
            ? response(['message' => __($status)])
            : throw ValidationException::withMessages([
                'email' => __($status),
            ]);
    }
}
