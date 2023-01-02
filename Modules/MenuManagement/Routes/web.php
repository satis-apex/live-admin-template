<?php
use Illuminate\Support\Facades\Route;
use Modules\MenuManagement\Http\Controllers\MenuController;
use Modules\MenuManagement\Http\Controllers\MenuLinkController;
use Modules\MenuManagement\Http\Controllers\MenuLinkPermissionController;

Route::middleware(['auth'])->prefix('menu-link')->group(function () {
    Route::get('/', [MenuLinkController::class, 'index'])->name('menuLink.index');
    Route::post('create', [MenuLinkController::class, 'store'])->name('menuLink.store');
    // Route::get('edit/{id}', [MenuLinkController::class, 'edit'])->name('menuLink.edit');
    Route::patch('edit/{id}', [MenuLinkController::class, 'update'])->name('menuLink.update');
    Route::delete('delete/{id}', [MenuLinkController::class, 'destroy'])->name('menuLink.destroy');
});

Route::middleware(['auth'])->group(function () {
    Route::resource('menu', MenuController::class, ['names' => 'menu'])->only(['store']);
    Route::resource('menu-link-permission', MenuLinkPermissionController::class, ['names' => 'menuLinkPermission'])->only(['destroy', 'update']);
});
