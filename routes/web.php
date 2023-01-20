<?php

use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Redirect;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\NotificationController;
use App\Http\Controllers\LandingPageController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Auth::routes([
//     'register' => false, // Registration Routes...
//     'reset' => false, // Password Reset Routes...
//     'verify' => false,
// ]);

// Route::get('/', function () {
//     // return Redirect::to('/dashboard');
//     // return Inertia::render('Welcome', [
//     //     'canLogin' => Route::has('login'),
//     //     'canRegister' => Route::has('register'),
//     //     'laravelVersion' => Application::VERSION,
//     //     'phpVersion' => PHP_VERSION,
//     // ]);
// });

Route::get('/', [LandingPageController::class, 'index'])->name('landing');
Route::middleware(['auth'])->group(function () {
    Route::get('dashboard', DashboardController::class)->name('dashboard');
    Route::patch('notification/mark-read', [NotificationController::class, 'markNotificationRead'])->name('markNotificationRead');
    Route::patch('notification/mark-all-read', [NotificationController::class, 'markAllNotificationRead'])->name('markAllNotificationRead');
});

require __DIR__ . '/auth.php';
Route::fallback(fn () => abort('404'));
