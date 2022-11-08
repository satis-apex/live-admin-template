<?php
use Illuminate\Support\Facades\Route;
use Modules\{Module}\Http\Controllers\{Module}Controller;

Route::middleware(['auth'])->prefix('{module-}')->group(function() {
    Route::get('/', [{Module}Controller::class, 'index'])->name('{routeName}.index');
    Route::post('create', [{Module}Controller::class, 'store'])->name('{routeName}.store');
    // Route::get('edit/{id}', [{Module}Controller::class, 'edit'])->name('{routeName}.edit');
    Route::patch('edit/{id}', [{Module}Controller::class, 'update'])->name('{routeName}.update');
    Route::delete('delete/{id}', [{Module}Controller::class, 'destroy'])->name('{routeName}.delete');
});
