<?php

namespace Tests\Feature;

use App\Http\Controllers\EmployeeController;
use App\Models\Employee;
use App\Models\EmployeeAddress;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Tests\TestCase;

class EmployeeTest extends TestCase
{
    use LazilyRefreshDatabase;

    public function test_employees_can_be_listed(): void
    {
        $employees = Employee::factory()->count(2)->create();

        $response = $this->getJson(action([EmployeeController::class, 'index'], ['include' => 'employeeAddress']));

        $response->assertSuccessful();
        $response->assertJsonCount($employees->count(), 'data');
        $response->assertJsonStructure([
            'data' => [
                '*' => [
                    'id',
                    'full_name',
                    'email',
                    'position',
                    'employee_address',
                ],
            ],
        ]);
    }

    public function test_employee_can_be_shown(): void
    {
        $employee = Employee::factory()->create();

        $response = $this->getJson(action([EmployeeController::class, 'show'], $employee));

        $response->assertSuccessful();
        $response->assertJsonStructure([
            'data' => [
                'id',
                'full_name',
                'email',
                'position',
                'employee_address',
            ],
        ]);
    }

    public function test_employee_can_be_created(): void
    {
        $employeeNew = Employee::factory()->make()->toArray();
        $employeeNew['employee_address'] = EmployeeAddress::factory()->make()->toArray();

        $response = $this->postJson(action([EmployeeController::class, 'store']), $employeeNew);

        $response->assertCreated();
        $this->assertDatabaseHas(Employee::class, ['full_name' => $employeeNew['full_name']]);
        $this->assertDatabaseHas(EmployeeAddress::class, ['residence_country' => $employeeNew['employee_address']['residence_country']]);
    }

    public function test_employee_can_be_updated(): void
    {
        $employee = Employee::factory()->create();
        $employeeNew = Employee::factory()->make()->toArray();
        $employeeNew['employee_address'] = EmployeeAddress::factory()->make()->toArray();

        $response = $this->putJson(action([EmployeeController::class, 'update'], $employee), $employeeNew);

        $response->assertAccepted();
        $this->assertDatabaseHas(Employee::class, ['full_name' => $employeeNew['full_name']]);
        $this->assertDatabaseHas(EmployeeAddress::class, ['residence_country' => $employeeNew['employee_address']['residence_country']]);
    }

    public function test_employee_can_be_deleted(): void
    {
        $employee = Employee::factory()->create();

        $response = $this->deleteJson(action([EmployeeController::class, 'destroy'], $employee));

        $response->assertNoContent();
        $this->assertDatabaseMissing(Employee::class, ['full_name' => $employee->full_name]);
        $this->assertDatabaseMissing(EmployeeAddress::class, ['employee_id' => $employee->id]);
    }
}
