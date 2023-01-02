<?php
use Illuminate\Support\Facades\Route;
use Modules\UserManagement\Http\Controllers\UserController;
use Modules\UserManagement\Http\Controllers\UserRoleController;
use Modules\UserManagement\Http\Controllers\ImpersonateController;
use Modules\UserManagement\Http\Controllers\UserProfileController;

Route::middleware(['auth'])->prefix('user-role')->group(function () {
    Route::get('/', [UserRoleController::class, 'index'])->name('userRole.index');
    Route::post('create', [UserRoleController::class, 'store'])->name('userRole.store');
    Route::post('mass-create', [UserRoleController::class, 'massStore'])->name('userRole.massStore');
    // Route::get('edit/{id}', [UserRoleController::class, 'edit'])->name('userRole.edit');
    Route::patch('edit/{id}', [UserRoleController::class, 'update'])->name('userRole.update');
    Route::delete('delete/{id}', [UserRoleController::class, 'destroy'])->name('userRole.destroy');
});

Route::middleware(['auth'])->prefix('user')->group(function () {
    Route::get('/', [UserController::class, 'index'])->name('user.index');
    Route::post('create', [UserController::class, 'store'])->name('user.store');
    // Route::get('edit/{id}', [UserController::class, 'edit'])->name('user.edit');
    Route::patch('edit/{id}', [UserController::class, 'update'])->name('user.update');
    Route::delete('delete/{id}', [UserController::class, 'destroy'])->name('user.destroy');
});

Route::middleware(['auth'])->group(function () {
    Route::resource('profile/edit', UserProfileController::class, ['names' => 'userProfile']);
    Route::post('profile/avatar', [UserProfileController::class, 'updateAvatar'])->name('updateAvatar');
    Route::post('user/impersonate', [ImpersonateController::class, 'store'])->name('user.impersonate');
    Route::delete('user/impersonate', [ImpersonateController::class, 'destroy'])->name('user.revert-impersonate');
});
