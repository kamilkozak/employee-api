<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\ForgotPasswordRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Password;
use Illuminate\Validation\ValidationException;

/**
 * @group Auth
 */
class ForgotPasswordController extends Controller
{
    /**
     * Forgot Password
     *
     * @bodyParam email string Example: test@example.com
     *
     * @response 200 {
     *       "message": "We have emailed your password reset link."
     *   }
     *
     * @unauthenticated
     */
    public function __invoke(ForgotPasswordRequest $request): JsonResponse
    {
        $status = Password::sendResetLink(
            $request->only('email')
        );

        return $status === Password::RESET_LINK_SENT
            ? response()->json(['message' => __($status)])
            : throw ValidationException::withMessages([
                'email' => __($status),
            ]);
    }
}
