<?php

namespace App\Http\Controllers;

use App\Http\Requests\BulkDestroyEmployeeRequest;
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

/**
 * @group Employee
 */
class EmployeeController extends Controller
{
    /**
     * Index Employee
     *
     * @queryParam include string Example: employeeAddress
     * @queryParam filter[full_name] string
     * @queryParam filter[email] string
     * @queryParam filter[position] string
     * @queryParam sort string
     * @queryParam page[number] int
     * @queryParam page[size] int
     *
     * @unauthenticated
     *
     * @apiResourceCollection App\Http\Resources\EmployeeResource
     *
     * @apiResourceModel App\Models\Employee
     */
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

    /**
     * Store Employee
     *
     * @apiResource status=201 App\Http\Resources\EmployeeResource
     *
     * @apiResourceModel App\Models\Employee
     */
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

    /**
     * Show Employee
     *
     * @apiResource App\Http\Resources\EmployeeResource
     *
     * @apiResourceModel App\Models\Employee
     */
    public function show(Employee $employee): EmployeeResource
    {
        return new EmployeeResource($employee->load('employeeAddress'));
    }

    /**
     * Update Employee
     *
     * @apiResource status=202 App\Http\Resources\EmployeeResource
     *
     * @apiResourceModel App\Models\Employee
     */
    public function update(UpdateEmployeeRequest $request, Employee $employee): JsonResponse
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

    /**
     * Destroy Employee
     *
     * @response 204
     */
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

    /**
     * Bulk Destroy Employee
     *
     * @bodyParam employee_ids array
     *
     * @response 204
     */
    public function bulkDestroy(BulkDestroyEmployeeRequest $request): JsonResponse
    {
        DB::beginTransaction();

        try {
            foreach ($request->validated('employee_ids') as $id) {
                $employee = Employee::find($id);
                $employee->delete();
                $employee->employeeAddress()->delete();
            }

            DB::commit();
        } catch (\Throwable $e) {
            DB::rollback();
            throw new UnprocessableEntityHttpException($e->getMessage());
        }

        return response()->json(status: Response::HTTP_NO_CONTENT);
    }
}
