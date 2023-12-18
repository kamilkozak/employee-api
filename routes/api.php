<?php

use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\EmployeeController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::prefix('v1')->group(function () {

    Route::post('login', LoginController::class)->name('auth.login');
    Route::post('forgot-password', ForgotPasswordController::class)->middleware('guest')->name('password.email');
    Route::post('reset-password', ResetPasswordController::class)->middleware('guest')->name('password.reset');

    Route::name('api.v1.')->group(function () {

        Route::get('employees', [EmployeeController::class, 'index'])->name('employees.index');

        Route::middleware('auth:sanctum')->group(function () {
            Route::post('employees', [EmployeeController::class, 'store'])->name('employees.store');
            Route::get('employees/{employee}', [EmployeeController::class, 'show'])->name('employee.show');
            Route::put('employees/{employee}', [EmployeeController::class, 'update'])->name('employee.update');
            Route::delete('employees/{employee}', [EmployeeController::class, 'destroy'])->name('employees.destroy');
            Route::delete('employees', [EmployeeController::class, 'bulkDestroy'])->name('employees.bulk-destroy');
        });
    });
});
