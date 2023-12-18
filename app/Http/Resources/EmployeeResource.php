<?php

namespace App\Http\Resources;

use App\Support\MoneyHelper;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin \App\Models\Employee */
class EmployeeResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'full_name' => $this->full_name,
            'email' => $this->email,
            'phone_number' => $this->phone_number,
            'average_salary_last_year' => MoneyHelper::format($this->average_salary_last_year),
            'position' => $this->position,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,

            'employee_address' => new EmployeeAddressResource($this->whenLoaded('employeeAddress')),

        ];
    }
}
