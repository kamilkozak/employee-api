<?php

namespace Database\Seeders;

use App\Models\Employee;
use App\Models\User;
use Illuminate\Database\Seeder;

class DummyDataSeeder extends Seeder
{
    public function run(): void
    {
        User::factory()->create([
            'email' => 'test@example.com',
            'is_active' => true,
        ])->tokens()->create([
            'name' => 'login-token',
            'token' => hash('sha256', 'N7fp6GTjO9CJD1QIhqv0Ty1ZZbJeS3tFIbToFJZQ'),
            'abilities' => ['*'],
        ]);

        Employee::factory()->count(20)->create();
    }
}
