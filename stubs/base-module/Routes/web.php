<?php
use Illuminate\Support\Facades\Route;
use Modules\{Module}\Http\Controllers\{Controller-}Controller;

Route::middleware(['auth'])->prefix('{Controller-}')->group(function() {
    Route::get('/', [{Controller}Controller::class, 'index'])->name('{routeName}.index');
    Route::post('create', [{Controller}Controller::class, 'store'])->name('{routeName}.store');
    Route::post('mass-create', [{Controller}Controller::class, 'massStore'])->name('{routeName}.massStore');
    // Route::get('edit/{id}', [{Module}Controller::class, 'edit'])->name('{routeName}.edit');
    Route::patch('edit/{id}', [{Controller}Controller::class, 'update'])->name('{routeName}.update');
    Route::delete('delete/{id}', [{Controller}Controller::class, 'destroy'])->name('{routeName}.destroy');
});
