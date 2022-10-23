<?php
namespace App\Http\Controllers\Admin;

use Inertia\Inertia;
use Illuminate\Support\Str;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Redirect;
use Facades\App\Services\UserPermissionCheckService;

class UserPermissionCheckController extends Controller
{
    protected $routeName = '';

    public function __construct()
    {
        $this->routeName = Str::camel('UserPermissionCheck');
        $this->implementMethodPermission('UserPermissionCheck');
    }

    public function index()
    {
        return Inertia::render(
            '',
            [
                'breadcrumb' => getBreadcrumb() ?: readable('UserPermissionCheck'),
                'userCan' => [
                    'create' => request()->user()->hasPermissionTo('UserPermissionCheck-create'),
                    'edit' => request()->user()->hasPermissionTo('UserPermissionCheck-edit'),
                    'delete' => request()->user()->hasPermissionTo('UserPermissionCheck-delete'),
                ]
            ]
        );
    }

    public function store()
    {
        try {
            UserPermissionCheckService::add();
        } catch (\Exception $e) {
            return Redirect::route($this->routeName . '.index')->with('error', $e->getMessage());
        }
        return Redirect::route($this->routeName . '.index')->with('success', ' Added Successfully');
    }

    public function update(int $id)
    {
        try {
            UserPermissionCheckService::update($id);
        } catch (\Exception $e) {
            return Redirect::route($this->routeName . '.index')->with('error', $e->getMessage());
        }
        return Redirect::route($this->routeName . '.index')->with('success', ' Added Successfully');
    }

    public function destroy(int $id)
    {
        try {
            UserPermissionCheckService::remove($id);
        } catch (\Exception $e) {
            return Redirect::route($this->routeName . '.index')->with('error', $e->getMessage());
        }
        return Redirect::route($this->routeName . '.index')->with('success', ' Deleted Successfully');
    }
}
