<?php
namespace Modules\EmployeeManagement\Http\Controllers;

use Inertia\Inertia;
use Modules\EmployeeManagement\Models\Employee;
use Spatie\Permission\Models\Role;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Redirect;
use Facades\Modules\EmployeeManagement\Services\EmployeeService;
use Modules\EmployeeManagement\Http\Requests\StoreEmployeeRequest;
use Modules\EmployeeManagement\Http\Requests\UpdateEmployeeRequest;

class EmployeeController extends Controller
{
    protected $routeName = 'employee';

    public function __construct()
    {
        $this->implementMethodPermission('Employee');
    }

    public function index()
    {
        $employeeList = Employee::query()
        ->with(['account.roles' => function ($query) {
            $query->select('id', 'name');
        }])
        ->with('account:id,profileable_id')
        ->latest()
        ->get();
        $userRole = request()->user()->roles[0]->name;
        $role = Role::query();
        if (isAuthorized('su-admin')) {
            $role->whereNotIn('name', ['su-admin']);
        }
        $roleList = $role->get()->toArray();
        return Inertia::render(
            'EmployeeManagement::Index',
            [
                'breadcrumb' => getBreadcrumb() ?: readable('Employee'),
                'employeeList' => $employeeList,
                'roleList' => $roleList,
                'userCan' => [
                    'massAdd' => true && request()->user()->hasPermissionTo('Employee-create'),
                    'create' => request()->user()->hasPermissionTo('Employee-create'),
                    'edit' => request()->user()->hasPermissionTo('Employee-edit'),
                    'delete' => request()->user()->hasPermissionTo('Employee-delete'),
                    'impersonate' => isAuthorized('su-admin'),
                ]
            ]
        );
    }

    public function store(StoreEmployeeRequest $request)
    {
        $request->validated();
        try {
            EmployeeService::add();
        } catch (\Exception $e) {
            return Redirect::route($this->routeName . '.index')->with('error', $e->getMessage());
        }
        return Redirect::route($this->routeName . '.index')->with('success', 'Added Successfully');
    }

    public function massStore()
    {
        try {
            EmployeeService::massAdd();
        } catch (\Exception $e) {
            return Redirect::route($this->routeName . '.index')->with('error', $e->getMessage());
        }
        return Redirect::route($this->routeName . '.index')->with('success', 'Multiple Data Added Successfully');
    }

    public function update(UpdateEmployeeRequest $request, int $id)
    {
        $request->validated();
        try {
            EmployeeService::update($id);
        } catch (\Exception $e) {
            return Redirect::route($this->routeName . '.index')->with('error', $e->getMessage());
        }
        return Redirect::route($this->routeName . '.index')->with('success', 'Updated Successfully');
    }

    public function destroy(int $id)
    {
        try {
            EmployeeService::remove($id);
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
            $employee = Employee::findOrFail(request('employeeId'));
            if ($employee->account != null) {
                return Redirect::route($this->routeName . '.index')->with('warning', 'User account already exists');
            }
            EmployeeService::createAccount($employee);
        } catch (\Exception $e) {
            return Redirect::route($this->routeName . '.index')->with('error', $e->getMessage());
        }

        return Redirect::route($this->routeName . '.index')->with('success', 'Successfully created Employee user account');
    }

    public function resetPassword()
    {
        if (!isAuthorized(['Admin', 'Su-Admin'])) {
            return back()->with('error', 'UnAuthorized Request');
        }
        try {
            $employee = Employee::findOrFail(request('employeeId'));
            if ($employee->email == null) {
                return Redirect::route($this->routeName . '.index')->with('warning', 'Please update Employee email address');
            }
            EmployeeService::resetPassword($employee);
        } catch (\Exception $e) {
            return Redirect::route($this->routeName . '.index')->with('error', $e->getMessage());
        }
        return Redirect::route($this->routeName . '.index')->with('success', 'Account password reset Successful');
    }
}
