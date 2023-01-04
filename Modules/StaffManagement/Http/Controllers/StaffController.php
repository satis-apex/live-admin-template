<?php
namespace Modules\StaffManagement\Http\Controllers;

use Inertia\Inertia;
use Modules\StaffManagement\Models\Staff;
use Spatie\Permission\Models\Role;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Redirect;
use Facades\Modules\StaffManagement\Services\StaffService;
use Modules\StaffManagement\Http\Requests\StoreStaffRequest;
use Modules\StaffManagement\Http\Requests\UpdateStaffRequest;

class StaffController extends Controller
{
    protected $routeName = 'staff';

    public function __construct()
    {
        $this->implementMethodPermission('Staff');
    }

    public function index()
    {
        $staffList = Staff::query()
        ->with(['account.roles' => function ($query) {
            $query->select('id', 'name');
        }])
        ->latest()
        ->get();
        $userRole = request()->user()->roles[0]->name;
        $role = Role::query();
        if (strtolower($userRole) != 'su-admin') {
            $role->whereNotIn('name', ['su-admin']);
        }
        $roleList = $role->get()->toArray();
        return Inertia::render(
            'StaffManagement::Index',
            [
                'breadcrumb' => getBreadcrumb() ?: readable('Staff'),
                'staffList' => $staffList,
                'roleList' => $roleList,
                'userCan' => [
                    'massAdd' => true && request()->user()->hasPermissionTo('Staff-create'),
                    'create' => request()->user()->hasPermissionTo('Staff-create'),
                    'edit' => request()->user()->hasPermissionTo('Staff-edit'),
                    'delete' => request()->user()->hasPermissionTo('Staff-delete'),
                ]
            ]
        );
    }

    public function store(StoreStaffRequest $request)
    {
        $request->validated();
        try {
            StaffService::add();
        } catch (\Exception $e) {
            return Redirect::route($this->routeName . '.index')->with('error', $e->getMessage());
        }
        return Redirect::route($this->routeName . '.index')->with('success', 'Added Successfully');
    }

    public function massStore()
    {
        try {
            StaffService::massAdd();
        } catch (\Exception $e) {
            return Redirect::route($this->routeName . '.index')->with('error', $e->getMessage());
        }
        return Redirect::route($this->routeName . '.index')->with('success', 'Multiple Data Added Successfully');
    }

    public function update(UpdateStaffRequest $request, int $id)
    {
        $request->validated();
        try {
            StaffService::update($id);
        } catch (\Exception $e) {
            return Redirect::route($this->routeName . '.index')->with('error', $e->getMessage());
        }
        return Redirect::route($this->routeName . '.index')->with('success', 'Updated Successfully');
    }

    public function destroy(int $id)
    {
        try {
            StaffService::remove($id);
        } catch (\Exception $e) {
            return Redirect::route($this->routeName . '.index')->with('error', $e->getMessage());
        }
        return Redirect::route($this->routeName . '.index')->with('success', 'Deleted Successfully');
    }

    public function createAccount()
    {
        if (!isAuthorized(['Admin', 'Su-Admin'])) {
            return back()->with('error', 'UnAuthorized Request');
        }
        try {
            $staff = Staff::findOrFail(request('staffId'));
            if ($staff->account != null) {
                return Redirect::route($this->routeName . '.index')->with('warning', 'User account already exists');
            }
            StaffService::createAccount($staff);
        } catch (\Exception $e) {
            return Redirect::route($this->routeName . '.index')->with('error', $e->getMessage());
        }

        return Redirect::route($this->routeName . '.index')->with('success', 'Successfully created Staff user account');
    }

    public function resetPassword()
    {
        if (!isAuthorized(['Admin', 'Su-Admin'])) {
            return back()->with('error', 'UnAuthorized Request');
        }
        try {
            $staff = Staff::findOrFail(request('staffId'));
            if ($staff->email == null) {
                return Redirect::route($this->routeName . '.index')->with('warning', 'Please update staff email address');
            }
            StaffService::resetPassword($staff);
        } catch (\Exception $e) {
            return Redirect::route($this->routeName . '.index')->with('error', $e->getMessage());
        }
        return Redirect::route($this->routeName . '.index')->with('success', 'Account password reset Successful');
    }
}
