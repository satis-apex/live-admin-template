<?php
namespace Modules\MenuManagement\Http\Controllers;

use App\Activators\Module;
use Inertia\Inertia;
use Modules\MenuManagement\Models\Menu;
use Spatie\Permission\Models\Role;
use App\Http\Controllers\Controller;
use Modules\MenuManagement\Models\MenuLink;
use Illuminate\Support\Facades\Redirect;
use Facades\Modules\MenuManagement\Services\MenuManagementService;

class MenuLinkController extends Controller
{
    protected $routeName = 'menuLink';

    public function __construct()
    {
        $this->implementMethodPermission('MenuLink');
    }

    public function index()
    {
        $menuRoutes = MenuManagementService::listableMenu();
        $parentLinks = MenuLink::where('type', 'parent')->get(['id', 'name']);
        $userRoles = Role::where('guard_name', 'web')->orderBy('id')->pluck('name')->toArray();
        $roleMenus = MenuManagementService::listRoleMenuPermission();
        $menuLinkLists = MenuLink::pluck('name')->toArray();
        $menuObject = Menu::pluck('menu_list')->first();
        $modules = Module::pluck('name')->toArray();
        return Inertia::render(
            'MenuManagement::Index',
            [
                'MenuObject' => json_decode($menuObject),
                'menuRoutes' => $menuRoutes,
                'parentLinks' => $parentLinks,
                'userRoles' => $userRoles,
                'modules' => $modules,
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
            MenuManagementService::add();
        } catch (\Exception $e) {
            return Redirect::route($this->routeName . '.index')->with('error', $e->getMessage());
        }
        return Redirect::route($this->routeName . '.index')->with('success', 'Menu Link Added Successfully');
    }

    public function update($menuId)
    {
        try {
            MenuManagementService::update($menuId);
        } catch (\Exception $e) {
            return Redirect::route($this->routeName . '.index')->with('error', $e->getMessage());
        }
        return Redirect::route($this->routeName . '.index')->with('success', 'Menu Link Updates Successfully');
    }

    public function destroy($menuId)
    {
        try {
            MenuManagementService::remove($menuId);
        } catch (\Exception $e) {
            return Redirect::route($this->routeName . '.index')->with('error', $e->getMessage());
        }
        return Redirect::route($this->routeName . '.index')->with('success', 'Menu Link Deleted Successfully');
    }
}
