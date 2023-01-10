<?php
use Illuminate\Support\Facades\Route;
use Modules\EventManagement\Http\Controllers\announcementController;

Route::middleware(['auth'])->prefix('announcement')->group(function() {
    Route::get('/', [AnnouncementController::class, 'index'])->name('announcement.index');
    Route::post('create', [AnnouncementController::class, 'store'])->name('announcement.store');
    Route::post('mass-create', [AnnouncementController::class, 'massStore'])->name('announcement.massStore');
    // Route::get('edit/{id}', [EventManagementController::class, 'edit'])->name('announcement.edit');
    Route::patch('edit/{id}', [AnnouncementController::class, 'update'])->name('announcement.update');
    Route::delete('delete/{id}', [AnnouncementController::class, 'destroy'])->name('announcement.destroy');
});
