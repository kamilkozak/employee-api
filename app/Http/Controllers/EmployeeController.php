<?php

namespace App\Http\Controllers;

use App\Http\Queries\EmployeeIndexQuery;
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
     * @queryParam filter[employee_addresses.residence_country] string
     * @queryParam sort string
     * @queryParam page[number] int Example: 1
     * @queryParam page[size] int Example: 5
     *
     * @unauthenticated
     *
     * @apiResourceCollection App\Http\Resources\EmployeeResource
     *
     * @apiResourceModel App\Models\Employee
     */
    public function index(EmployeeIndexQuery $query): AnonymousResourceCollection
    {
        return EmployeeResource::collection($query->jsonPaginate());
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
