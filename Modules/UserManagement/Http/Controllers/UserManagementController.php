<?php

namespace Modules\UserManagement\Http\Controllers;

use Inertia\Inertia;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Redirect;
use Facades\Modules\UserManagement\Services\UserManagementService;
use Modules\UserManagement\Models\UserManagement;

class UserManagementController extends Controller
{
    protected $routeName = 'userManagement';
    public function __construct()
    {
        $this->implementMethodPermission('UserManagement');
    }

    public function index()
    {
        $userManagementList = [
            [
                'date' => '2016-05-03',
                'name' => 'Tom',
                'address' => 'No. 189, Grove St, Los Angeles',
                'status' => 'Active',
            ],
            [
                'date' => '2016-05-02',
                'name' => 'John',
                'address' => 'No. 199, Grove St, Los Angeles',
                'status' => 'Active',
            ],
        ]; // UserManagement::query()->get('name');
        return Inertia::render(
			'UserManagement::Index',
			[
				'breadcrumb' => getBreadcrumb() ?: readable('UserManagement'),
                'userManagementList' => $userManagementList,
				'userCan' => [
                    'massAdd' => true && request()->user()->hasPermissionTo('UserManagement-create'),
                    'create' => request()->user()->hasPermissionTo('UserManagement-create'),
                    'edit' => request()->user()->hasPermissionTo('UserManagement-edit'),
                    'delete' => request()->user()->hasPermissionTo('UserManagement-delete'),
                ]
			]
		);
    }

    public function store()
    {
		try {
           UserManagementService::add();
        } catch (\Exception $e) {
            return Redirect::route($this->routeName.'.index')->with('error', $e->getMessage());
        }
        return Redirect::route($this->routeName.'.index')->with('success', 'Added Successfully');
    }

    public function massStore()
    {
		try {
           UserManagementService::massAdd();
        } catch (\Exception $e) {
            return Redirect::route($this->routeName.'.index')->with('error', $e->getMessage());
        }
        return Redirect::route($this->routeName.'.index')->with('success', 'Multiple Data Added Successfully');
    }

    public function update(int $id)
    {
		 try {
           UserManagementService::update($id);
        } catch (\Exception $e) {
            return Redirect::route($this->routeName.'.index')->with('error', $e->getMessage());
        }
        return Redirect::route($this->routeName.'.index')->with('success', 'Updated Successfully');
    }

    public function destroy(int $id)
    {
		 try {
            UserManagementService::remove($id);
        } catch (\Exception $e) {
            return Redirect::route($this->routeName.'.index')->with('error', $e->getMessage());
        }
        return Redirect::route($this->routeName.'.index')->with('success', 'Deleted Successfully');
    }
}
