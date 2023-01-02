<?php
namespace Modules\UserManagement\Http\Controllers;

use Inertia\Inertia;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Redirect;
use Facades\Modules\UserManagement\Services\UserService;

class UserController extends Controller
{
    protected $routeName = 'user';

    public function __construct()
    {
        $this->implementMethodPermission('User');
    }

    public function index()
    {
        return Inertia::render(
            'User::Index',
            [
                'breadcrumb' => getBreadcrumb() ?: readable('User'),
                'userCan' => [
                    'create' => request()->user()->hasPermissionTo('User-create'),
                    'edit' => request()->user()->hasPermissionTo('User-edit'),
                    'delete' => request()->user()->hasPermissionTo('User-delete'),
                ]
            ]
        );
    }

    public function store()
    {
        try {
            UserService::add();
        } catch (\Exception $e) {
            return Redirect::route($this->routeName . '.index')->with('error', $e->getMessage());
        }
        return Redirect::route($this->routeName . '.index')->with('success', ' Added Successfully');
    }

    public function update(int $id)
    {
        try {
            UserService::update($id);
        } catch (\Exception $e) {
            return Redirect::route($this->routeName . '.index')->with('error', $e->getMessage());
        }
        return Redirect::route($this->routeName . '.index')->with('success', ' Updated Successfully');
    }

    public function destroy(int $id)
    {
        try {
            UserService::remove($id);
        } catch (\Exception $e) {
            return Redirect::route($this->routeName . '.index')->with('error', $e->getMessage());
        }
        return Redirect::route($this->routeName . '.index')->with('success', ' Deleted Successfully');
    }
}
