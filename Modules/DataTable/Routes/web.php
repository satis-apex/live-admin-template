<?php
use Illuminate\Support\Facades\Route;
use Modules\DataTable\Http\Controllers\DataTableController;

Route::middleware(['auth'])->prefix('data-table')->group(function () {
    Route::get('/', [DataTableController::class, 'index'])->name('dataTable.index');
    Route::post('create', [DataTableController::class, 'store'])->name('dataTable.store');
    Route::post('mass-create', [DataTableController::class, 'massStore'])->name('dataTable.massStore');
    // Route::get('edit/{id}', [DataTableController::class, 'edit'])->name('dataTable.edit');
    Route::patch('edit/{id}', [DataTableController::class, 'update'])->name('dataTable.update');
    Route::delete('delete/{id}', [DataTableController::class, 'destroy'])->name('dataTable.destroy');
});
