<?php

namespace Database\Factories;

use App\Models\Employee;
use App\Models\EmployeeAddress;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class EmployeeAddressFactory extends Factory
{
    protected $model = EmployeeAddress::class;

    public function definition(): array
    {
        return [
            'residence_country' => fake()->country(),
            'residence_zip_code' => fake()->postcode(),
            'residence_city' => fake()->city(),
            'residence_street' => fake()->streetName(),
            'residence_building_number' => fake()->randomNumber(),
            'residence_apartment_number' => fake()->randomNumber(),
            'different_correspondence_address' => fake()->boolean(),
            'correspondence_country' => fake()->country(),
            'correspondence_zip_code' => fake()->postcode(),
            'correspondence_city' => fake()->city(),
            'correspondence_street' => fake()->streetName(),
            'correspondence_building_number' => fake()->randomNumber(),
            'correspondence_apartment_number' => fake()->randomNumber(),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),

            'employee_id' => Employee::factory(),
        ];
    }
}
