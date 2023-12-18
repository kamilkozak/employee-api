<?php

namespace App\Http\Queries;

use App\Models\Employee;
use Illuminate\Http\Request;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class EmployeeIndexQuery extends QueryBuilder
{
    public function __construct(Request $request)
    {
        $query = Employee::query()
            ->join('employee_addresses', 'employee_addresses.employee_id', '=', 'employees.id');

        parent::__construct($query, $request);

        $this->allowedIncludes([
            'employeeAddress',
        ]);

        $this->allowedFilters([
            AllowedFilter::exact('full_name'),
            AllowedFilter::exact('email'),
            AllowedFilter::exact('position'),
            AllowedFilter::exact('employee_addresses.residence_country'),
        ]);

        $this->allowedSorts([
            'full_name',
            'email',
            'average_salary_last_year',
            'position',
            'employee_addresses.residence_country',
        ]);
    }
}
