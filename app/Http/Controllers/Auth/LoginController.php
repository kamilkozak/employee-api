<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

/**
 * @group Auth
 */
class LoginController extends Controller
{
    /**
     * Login
     *
     * @bodyParam email string Example: test@example.com
     * @bodyParam password string Example: password
     *
     * @response 200 {
     *     "access_token": "2|sB3OxyfAoIHQX5zVPk5mp3ngtzm4Of72l0N3CuBR3e405f9e"
     * }
     *
     * @unauthenticated
     */
    public function __invoke(LoginRequest $request): JsonResponse
    {
        $user = User::where('email', $request->email)->first();

        if (! $user || ! Hash::check($request->validated('password'), $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.'],
            ]);
        }

        if (! $user->is_active) {
            throw ValidationException::withMessages([
                'email' => ['The User is inactive.'],
            ]);
        }

        return response()->json([
            'access_token' => $user->createToken($request->email)->plainTextToken,
        ]);
    }
}
