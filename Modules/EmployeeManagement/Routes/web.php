<?php
use Illuminate\Support\Facades\Route;
use Modules\EmployeeManagement\Http\Controllers\EmployeeController;

Route::middleware(['auth'])->prefix('employee')->group(function () {
    Route::get('/', [EmployeeController::class, 'index'])->name('employee.index');
    Route::post('create', [EmployeeController::class, 'store'])->name('employee.store');
    Route::post('mass-create', [EmployeeController::class, 'massStore'])->name('employee.massStore');
    // Route::get('edit/{id}', [EmployeeController::class, 'edit'])->name('employee.edit');
    Route::patch('edit/{id}', [EmployeeController::class, 'update'])->name('employee.update');
    Route::delete('delete/{id}', [EmployeeController::class, 'destroy'])->name('employee.destroy');
    Route::post('create-account', [EmployeeController::class, 'createAccount'])->name('employee.createAccount');
    Route::post('reset-password', [EmployeeController::class, 'resetPassword'])->name('employee.resetPassword');
});
