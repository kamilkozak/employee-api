<?php

namespace Tests;

use App\Models\User;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Laravel\Sanctum\Sanctum;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    protected function actingAsUser(?User $user = null): User
    {
        Sanctum::actingAs(
            $user ??= User::factory()->create(),
            ['*']
        );

        return $user;
    }
}
