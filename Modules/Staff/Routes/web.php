<?php
use Illuminate\Support\Facades\Route;
use Modules\Staff\Http\Controllers\StaffController;

Route::middleware(['auth'])->prefix('staff')->group(function () {
    Route::get('/', [StaffController::class, 'index'])->name('staff.index');
    Route::post('create', [StaffController::class, 'store'])->name('staff.store');
    Route::post('mass-create', [StaffController::class, 'massStore'])->name('staff.massStore');
    // Route::get('edit/{id}', [StaffController::class, 'edit'])->name('staff.edit');
    Route::patch('edit/{id}', [StaffController::class, 'update'])->name('staff.update');
    Route::delete('delete/{id}', [StaffController::class, 'destroy'])->name('staff.destroy');
    Route::post('create-account', [StaffController::class, 'createAccount'])->name('staff.createAccount');
    Route::post('reset-password', [StaffController::class, 'resetPassword'])->name('staff.resetPassword');
});
