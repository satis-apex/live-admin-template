<?php
namespace Modules\UserManagement\Http\Controllers;

use Inertia\Inertia;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Redirect;
use Facades\Modules\UserManagement\Services\UserRoleService;
use Spatie\Permission\Models\Role;

class UserRoleController extends Controller
{
    protected $routeName = 'userRole';

    public function __construct()
    {
        $this->implementMethodPermission('UserRole');
    }

    public function index()
    {
        $userRoleLists = Role::withCount('users')->get();
        return Inertia::render(
            'UserManagement::UserRole/Index',
            [
                'breadcrumb' => getBreadcrumb() ?: readable('UserRole'),
                'userRoleLists' => $userRoleLists,
                'userCan' => [
                    'massAdd' => true && request()->user()->hasPermissionTo('UserRole-create'),
                    'create' => request()->user()->hasPermissionTo('UserRole-create'),
                    'edit' => request()->user()->hasPermissionTo('UserRole-edit'),
                    'delete' => request()->user()->hasPermissionTo('UserRole-delete'),
                ]
            ]
        );
    }

    public function store()
    {
        try {
            UserRoleService::add();
        } catch (\Exception $e) {
            return Redirect::route($this->routeName . '.index')->with('error', $e->getMessage());
        }
        return Redirect::route($this->routeName . '.index')->with('success', 'Added Successfully');
    }

    public function massStore()
    {
        try {
            UserRoleService::massAdd();
        } catch (\Exception $e) {
            return Redirect::route($this->routeName . '.index')->with('error', $e->getMessage());
        }
        return Redirect::route($this->routeName . '.index')->with('success', 'Multiple Data Added Successfully');
    }

    public function update(int $id)
    {
        try {
            if (\in_array($id, [1, 2])) {
                return Redirect::route($this->routeName . '.index')->with('error', 'Cannot Modify System Role');
            }
            UserRoleService::update($id);
        } catch (\Exception $e) {
            return Redirect::route($this->routeName . '.index')->with('error', $e->getMessage());
        }
        return Redirect::route($this->routeName . '.index')->with('success', 'Updated Successfully');
    }

    public function destroy(int $id)
    {
        try {
            if (\in_array($id, [1, 2])) {
                return Redirect::route($this->routeName . '.index')->with('error', 'Cannot Delete System Role');
            }
            UserRoleService::remove($id);
        } catch (\Exception $e) {
            return Redirect::route($this->routeName . '.index')->with('error', $e->getMessage());
        }
        return Redirect::route($this->routeName . '.index')->with('success', 'Deleted Successfully');
    }
}
