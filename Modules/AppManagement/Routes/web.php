<?php
use Illuminate\Support\Facades\Route;
use Modules\AppManagement\Http\Controllers\AppSettingController;

Route::middleware(['auth'])->prefix('app-setting')->group(function () {
    Route::get('/', [AppSettingController::class, 'index'])->name('appSetting.index');
    Route::patch('update', [AppSettingController::class, 'update'])->name('appSetting.update');
});
