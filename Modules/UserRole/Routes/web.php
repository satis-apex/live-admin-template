<?php
use Illuminate\Support\Facades\Route;
use Modules\UserRole\Http\Controllers\UserRoleController;

Route::middleware(['auth'])->prefix('user-role')->group(function() {
    Route::get('/', [UserRoleController::class, 'index'])->name('userRole.index');
    Route::post('create', [UserRoleController::class, 'store'])->name('userRole.store');
    Route::post('mass-create', [UserRoleController::class, 'massStore'])->name('userRole.massStore');
    // Route::get('edit/{id}', [UserRoleController::class, 'edit'])->name('userRole.edit');
    Route::patch('edit/{id}', [UserRoleController::class, 'update'])->name('userRole.update');
    Route::delete('delete/{id}', [UserRoleController::class, 'destroy'])->name('userRole.destroy');
});
