<?php
namespace Modules\MenuLink\Http\Controllers;

use Inertia\Inertia;
use Modules\MenuLink\Models\Menu;
use Spatie\Permission\Models\Role;
use App\Http\Controllers\Controller;
use Modules\MenuLink\Models\MenuLink;
use Illuminate\Support\Facades\Redirect;
use Facades\Modules\MenuLink\Services\MenuLinkService;

class MenuLinkController extends Controller
{
    protected $routeName = 'menuLink';

    public function __construct()
    {
        $this->implementMethodPermission('MenuLink');
    }

    public function index()
    {
        $menuRoutes = MenuLinkService::listableMenu();
        $parentLinks = MenuLink::where('type', 'parent')->get(['id', 'name']);
        $userRoles = Role::where('guard_name', 'web')->orderBy('id')->pluck('name')->toArray();
        $roleMenus = MenuLinkService::listRoleMenuPermission();
        $menuLinkLists = MenuLink::pluck('name')->toArray();
        $menuObject = Menu::pluck('menu_list')->first();
        // dd(json_decode($menuObject));
        return Inertia::render(
            'MenuLink::Index',
            [
                'MenuObject' => json_decode($menuObject),
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
