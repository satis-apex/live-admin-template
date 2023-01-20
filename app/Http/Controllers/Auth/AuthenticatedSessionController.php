<?php
namespace App\Http\Controllers\Auth;

use Inertia\Inertia;
use Illuminate\Http\Request;
use Modules\MenuManagement\Models\Menu;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Providers\RouteServiceProvider;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Support\Facades\Redirect;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     *
     * @return \Inertia\Response
     */
    public function create()
    {
        Inertia::setRootView('adminApp');
        if (Auth::check()) {
            return Redirect::to('/dashboard');
        }
        return Inertia::render('Auth/Login', [
            'canResetPassword' => Route::has('password.request'),
            'status' => session('status'),
        ]);
    }

    /**
     * Handle an incoming authentication request.
     *
     * @param  \App\Http\Requests\Auth\LoginRequest  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(LoginRequest $request)
    {
        $request->authenticate();

        $request->session()->regenerate();
        // adds user info to session after successful login
        Inertia::share([
            'config' => [
                'a' => 'aaa',
                'b' => 'bbb'
            ]
        ]);

        Inertia::share(
            'auth',
            fn (Request $request) => $request->user()
                ? ['user' => $request->user()->only('id', 'email'), 'profile' => $request->user()->profile]
                : null
        );
        Inertia::share(
            'app_menu',
            fn (Request $request) => $request->user()
                ? Menu::first('menu_list')
                : null
        );

        return redirect()->intended(RouteServiceProvider::HOME);
    }

    /**
     * Destroy an authenticated session.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Request $request)
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
