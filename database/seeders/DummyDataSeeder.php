<?php

namespace Database\Seeders;

use App\Models\Employee;
use App\Models\EmployeeAddress;
use Illuminate\Database\Seeder;

class DummyDataSeeder extends Seeder
{
    public function run(): void
    {
        Employee::factory()
            ->has(EmployeeAddress::factory())
            ->count(10)
            ->create();
    }
}
