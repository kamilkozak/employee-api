<?php

namespace Database\Factories;

use App\Enums\Position;
use App\Models\Employee;
use App\Models\EmployeeAddress;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class EmployeeFactory extends Factory
{
    protected $model = Employee::class;

    public function definition(): array
    {
        return [
            'full_name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'phone_number' => fake()->phoneNumber(),
            'average_salary_last_year' => fake()->randomNumber(),
            'position' => fake()->randomElement(Position::cases()),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];
    }

    public function configure(): static
    {
        return $this->afterCreating(function (Employee $employee) {
            EmployeeAddress::factory()->create([
                'employee_id' => $employee->id,
            ]);
        });
    }
}
