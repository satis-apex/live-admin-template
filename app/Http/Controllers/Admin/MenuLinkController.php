<?php
namespace App\Http\Controllers\Admin;

use Inertia\Inertia;
use App\Models\MenuLink;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Role;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Redirect;
use Facades\App\Services\MenuLinkService;

class MenuLinkController extends Controller
{
    protected $routeName = '';

    public function __construct()
    {
        $this->routeName = Str::camel('menuLink');
        $this->implementMethodPermission('MenuLink');
    }

    public function index()
    {
        $menuRoutes = MenuLinkService::listableMenu();
        $parentLinks = MenuLink::where('type', 'parent')->get(['id', 'name']);
        $userRoles = Role::where('guard_name', 'web')->orderBy('id')->pluck('name')->toArray();
        $roleMenus = MenuLinkService::listRoleMenuPermission();
        $menuLinkLists = MenuLink::pluck('name')->toArray();
        return Inertia::render(
            'Admin/MenuManage/Index',
            [
                'menuRoutes' => $menuRoutes,
                'parentLinks' => $parentLinks,
                'userRoles' => $userRoles,
                'roleMenus' => $roleMenus,
                'menuLinkLists' => $menuLinkLists,
                'breadcrumb' => getBreadcrumb(),
                'userCan' => [
                    'create' => request()->user()->hasPermissionTo('MenuLink-create'),
                    'edit' => request()->user()->hasPermissionTo('MenuLink-edit'),
                    'delete' => request()->user()->hasPermissionTo('MenuLink-delete'),
                    'generate' => !app()->environment('production')
                ]
            ]
        );
    }

    public function store()
    {
        try {
            MenuLinkService::add();
        } catch (\Exception $e) {
            return Redirect::route($this->routeName . '.index')->with('error', $e->getMessage());
        }
        return Redirect::route($this->routeName . '.index')->with('success', 'Menu Link Added Successfully');
    }

    public function update($menuId)
    {
        try {
            MenuLinkService::update($menuId);
        } catch (\Exception $e) {
            return Redirect::route($this->routeName . '.index')->with('error', $e->getMessage());
        }
        return Redirect::route($this->routeName . '.index')->with('success', 'Menu Link Updates Successfully');
    }

    public function destroy($menuId)
    {
        try {
            MenuLinkService::remove($menuId);
        } catch (\Exception $e) {
            return Redirect::route($this->routeName . '.index')->with('error', $e->getMessage());
        }
        return Redirect::route($this->routeName . '.index')->with('success', 'Menu Link Deleted Successfully');
    }
}
