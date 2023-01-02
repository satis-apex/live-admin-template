<?php
namespace Modules\UserManagement\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class ImpersonateController extends Controller
{
    public function __construct()
    {
        $this->implementMethodPermission('Impersonate');
    }

    public function store()
    {
        if (Auth::user()->hasRole('Admin') || Auth::user()->hasRole('Su-Admin')) {
            $userId = request('userId');
            $originalId = Auth::guard('web')->id();
            session()->put('impersonator', $originalId);
            session()->put('impersonating', $userId);
            auth()->loginUsingId($userId);
            return redirect('/dashboard');
        }
        return back()->with('error', 'UnAuthorized Request');
    }

    public function destroy()
    {
        if (session()->has('impersonator')) {
            $originalId = session()->pull('impersonator');
            session()->forget('impersonating');
            auth()->loginUsingId($originalId);
        }
        return redirect('/dashboard');
    }
}
