<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreEmployeeRequest;
use App\Http\Requests\UpdateEmployeeRequest;
use App\Http\Resources\EmployeeResource;
use App\Models\Employee;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;

class EmployeeController extends Controller
{
    public function index(): AnonymousResourceCollection
    {
        $query = Employee::query()
            ->join('employee_addresses', 'employee_addresses.employee_id', '=', 'employees.id');

        $employees = QueryBuilder::for($query)
            ->allowedIncludes([
                'employeeAddress',
            ])
            ->allowedFilters([
                AllowedFilter::exact('full_name'),
                AllowedFilter::exact('email'),
                AllowedFilter::exact('position'),
            ])
            ->allowedSorts([
                'full_name',
                'email',
                'average_salary_last_year',
                'position',
            ]);

        return EmployeeResource::collection($employees->jsonPaginate());
    }

    public function store(StoreEmployeeRequest $request): JsonResponse
    {
        DB::beginTransaction();

        try {
            $employee = Employee::create(Arr::except($request->validated(), 'employee_address'));
            $employee->employeeAddress()->create($request->validated('employee_address'));

            DB::commit();
        } catch (\Throwable $e) {
            DB::rollback();
            throw new UnprocessableEntityHttpException($e->getMessage());
        }

        return (new EmployeeResource($employee->load('employeeAddress')))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    public function show(Employee $employee): EmployeeResource
    {
        return new EmployeeResource($employee->load('employeeAddress'));
    }

    public function update(UpdateEmployeeRequest $request, Employee $employee)
    {
        DB::beginTransaction();

        try {
            $employee->update(Arr::except($request->validated(), 'employee_address'));
            $employee->employeeAddress()->update($request->validated('employee_address'));

            DB::commit();
        } catch (\Throwable $e) {
            DB::rollback();
            throw new UnprocessableEntityHttpException($e->getMessage());
        }

        return (new EmployeeResource($employee->load('employeeAddress')))
            ->response()
            ->setStatusCode(Response::HTTP_ACCEPTED);
    }

    public function destroy(Employee $employee): JsonResponse
    {
        DB::beginTransaction();

        try {
            $employee->delete();
            $employee->employeeAddress()->delete();

            DB::commit();
        } catch (\Throwable $e) {
            DB::rollback();
            throw new UnprocessableEntityHttpException($e->getMessage());
        }

        return response()->json(status: Response::HTTP_NO_CONTENT);
    }
}
