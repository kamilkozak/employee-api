<?php

namespace App\Http\Requests;

use App\Enums\Position;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;

class StoreEmployeeRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'full_name' => ['required', 'string'],
            'email' => ['required', 'email', 'max:254'],
            'phone_number' => ['nullable', 'string'],
            'average_salary_last_year' => ['nullable', 'integer'],
            'position' => ['required', new Enum(Position::class)],
            'employee_address' => ['required', 'array'],
            'employee_address.residence_country' => ['required', 'string'],
            'employee_address.residence_zip_code' => ['required'],
            'employee_address.residence_city' => ['required', 'string'],
            'employee_address.residence_street' => ['required', 'string'],
            'employee_address.residence_building_number' => ['required'],
            'employee_address.residence_apartment_number' => ['nullable'],
            'employee_address.different_correspondence_address' => ['required', 'boolean'],
            'employee_address.correspondence_country' => ['required_if_accepted:employee_address.different_correspondence_address', 'string'],
            'employee_address.correspondence_zip_code' => ['required_if_accepted:employee_address.different_correspondence_address'],
            'employee_address.correspondence_city' => ['required_if_accepted:employee_address.different_correspondence_address', 'string'],
            'employee_address.correspondence_street' => ['required_if_accepted:employee_address.different_correspondence_address', 'string'],
            'employee_address.correspondence_building_number' => ['required_if_accepted:employee_address.different_correspondence_address'],
            'employee_address.correspondence_apartment_number' => ['nullable'],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
