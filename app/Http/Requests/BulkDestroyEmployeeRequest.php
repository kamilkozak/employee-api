<?php

namespace App\Http\Requests;

use App\Models\Employee;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class BulkDestroyEmployeeRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'employee_ids' => ['required', 'array'],
            'employee_ids.*' => ['required', 'integer', Rule::exists(Employee::class, 'id')],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
