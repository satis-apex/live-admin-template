<?php
use Illuminate\Support\Facades\Route;
use Modules\User\Http\Controllers\UserController;
use Modules\User\Http\Controllers\UserProfileController;

Route::middleware(['auth'])->prefix('user')->group(function () {
    Route::get('/', [UserController::class, 'index'])->name('user.index');
    Route::post('create', [UserController::class, 'store'])->name('user.store');
    // Route::get('edit/{id}', [UserController::class, 'edit'])->name('user.edit');
    Route::patch('edit/{id}', [UserController::class, 'update'])->name('user.update');
    Route::delete('delete/{id}', [UserController::class, 'destroy'])->name('user.delete');
});

Route::middleware(['auth'])->group(function () {
    Route::resource('profile/edit', UserProfileController::class, ['names' => 'userProfile']);
    Route::post('profile/avatar', [UserProfileController::class, 'updateAvatar'])->name('updateAvatar');
});
