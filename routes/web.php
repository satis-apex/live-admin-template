<?php

use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Redirect;
use App\Http\Controllers\Admin\MenuController;
use App\Http\Controllers\Admin\MenuLinkController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\MenuLinkPermissionController;

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

Route::get('/', function () {
    return Redirect::to('/dashboard');
    // return Inertia::render('Welcome', [
    //     'canLogin' => Route::has('login'),
    //     'canRegister' => Route::has('register'),
    //     'laravelVersion' => Application::VERSION,
    //     'phpVersion' => PHP_VERSION,
    // ]);
});
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard.user');
    Route::resource('menu', MenuController::class)->only(['store']);
    Route::resource('menu-link', MenuLinkController::class);
    Route::resource('menu-link-permission', MenuLinkPermissionController::class)->only(['destroy', 'update']);
});

require __DIR__ . '/auth.php';
Route::fallback(fn () => abort('404'));
