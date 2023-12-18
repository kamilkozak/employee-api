<?php

namespace App\Http\Requests;

use App\Models\Employee;
use Illuminate\Validation\Rule;

class UpdateEmployeeRequest extends StoreEmployeeRequest
{
    public function rules(): array
    {
        /** @var Employee $employee */
        $employee = $this->employee;

        return array_merge(parent::rules(), [
            'email' => ['required', 'email', 'max:254', Rule::unique(Employee::class)->ignore($employee)],
        ]);
    }
}
