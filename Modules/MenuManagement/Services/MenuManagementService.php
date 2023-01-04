<?php
namespace Modules\MenuManagement\Services;

use App\Activators\Module;
use Illuminate\Support\Str;
use Modules\MenuManagement\Models\Menu;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Route;
use Modules\MenuManagement\Models\MenuLink;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Artisan;
use Spatie\Permission\Models\Permission;

class MenuManagementService
{
    protected $permissions = ['access', 'create', 'edit', 'delete'];
    protected $permissionList = [];

    public function add()
    {
        try {
            $routeLink = request('link');
            $routeNameArray = explode('.', $routeLink);
            $routeName = reset($routeNameArray);
            $controllerName = Str::studly($routeName);
            if ($routeLink == 'auto-generate') {
                $module = request('module');
                $controllerName = request('controllerName');
                $generatedRouteLink = SELF::generateFiles($module, $controllerName);
                // $controllerName = Str::studly(class_basename($moduleName));
                $routeLink = $generatedRouteLink . '.index';
            } else {
                $link = request('link');
                $module = getRoutesModule($link);
            }
            $generateOption = request('generateOption');
            if ($generateOption == 'files-only') {
                return;
            }

            $createdMenu = MenuLink::create(
                [
                    'name' => request('name'),
                    'link' => $routeLink,
                    'icon' => request('icon'),
                    'parent_id' => request('parentId'),
                    'permission_key' => $controllerName,
                    'module' => $module,
                    'type' => request('type'),
                    'access' => implode(',', request('access')),
                    'default_menu' => false
                ]
            );

            $new_menu = (object) [
                'id' => $createdMenu->id,
                'link' => $createdMenu->link,
                'name' => $createdMenu->name,
                'icon' => $createdMenu->icon,
                'type' => $createdMenu->type,
                'access' => $createdMenu->access,
                'parent_id' => $createdMenu->parent_id,
                'module' => $createdMenu->module,
                'default_menu' => $createdMenu->default_menu
            ];
            $menus = Menu::first();
            $app_menu = json_decode($menus->menu_list);
            if ($createdMenu->type == 'parent' || $createdMenu->type == 'parent-single') {
                array_push($app_menu, $new_menu);
            } else {
                foreach ($app_menu as $key => $value) {
                    if ($value->id == $createdMenu->parent_id) {
                        $parentMenu = $app_menu[$key];
                        if (property_exists($parentMenu, 'children')) {
                            array_push($parentMenu->children, $new_menu);
                        } else {
                            $parentMenu->{'children'} = [$new_menu];
                        }
                    }
                }
            }
            $menus->menu_list = $app_menu;
            $menus->save();

            if ($createdMenu->type != 'parent') {
                app()->make(\Spatie\Permission\PermissionRegistrar::class)->forgetCachedPermissions();
                SELF::createPermissions(Str::studly($createdMenu->permission_key));
                SELF::assignPermissions($createdMenu->access);
            }
            return true;
        } catch (QueryException $e) {
            //database related exception
            return throw new  \Exception($e->errorInfo[2]);
        } catch (\Exception $e) {
            //general exception
            return throw new  \Exception($e->getMessage());
        }
    }

    public function update($menuId)
    {
        if (request('link')) {
            $routeLink = request('link');
            $routeNameArray = explode('.', $routeLink);
            $routeName = reset($routeNameArray);
            $controllerName = Str::studly($routeName);
        }

        try {
            $new_menu = (object) [
                'name' => request('name'),
                'link' => request('link') ? $routeLink : null,
                'icon' => request('icon'),
                'parent_id' => request('parentId'),
                'type' => request('type'),
                'module' => request('module'),
                'access' => implode(',', request('access')),
                'permission_key' => request('link') ? $controllerName : null,
            ];
            $menuLink = MenuLink::find($menuId);
            $previousAccess = $menuLink->access;
            $menuLink->name = $new_menu->name;
            $menuLink->access = $new_menu->access;
            switch ($menuLink->type) {
                case 'child':
                    $oldParent = $menuLink->parent_id;
                    $menuLink->link = $new_menu->link;
                    $menuLink->permission_key = $new_menu->permission_key;
                    $menuLink->parent_id = $new_menu->parent_id;
                    $new_menu->{'id'} = $menuId;
                    break;
                case 'parent':
                    $menuLink->icon = $new_menu->icon;
                    break;
                case 'parent-single':
                    $menuLink->link = $new_menu->link;
                    $menuLink->permission_key = $new_menu->permission_key;
                    $menuLink->icon = $new_menu->icon;
                    break;
                default:
                    break;
            }
            $menuLink->save();
            $menus = Menu::first();
            $app_menu = json_decode($menus->menu_list);
            foreach ($app_menu as $key => $value) {
                if ($new_menu->type != 'child') {
                    if ($value->id == $menuId) {
                        $app_menu[$key]->name = $new_menu->name;
                        $app_menu[$key]->link = $new_menu->link;
                        $app_menu[$key]->icon = $new_menu->icon;
                        $app_menu[$key]->type = $new_menu->type;
                        $app_menu[$key]->access = $new_menu->access;
                    }
                } else {
                    if ($oldParent == $new_menu->parent_id && $value->id == $new_menu->parent_id) {
                        foreach ($app_menu[$key]->children as $subKey => $subValue) {
                            if ($subValue->id == $menuId) {
                                $app_menu[$key]->children[$subKey] = $new_menu;
                            }
                        }
                    } elseif ($oldParent != $new_menu->parent_id) {
                        if ($value->id == $oldParent) {
                            foreach ($app_menu[$key]->children as $subKey => $subValue) {
                                if ($subValue->id == $menuId) {
                                    array_splice($app_menu[$key]->children, $subKey, 1);
                                    // $app_menu[$key]->children[$subKey] = null;
                                }
                            }
                        }
                        if ($value->id == $new_menu->parent_id) {
                            $parentMenu = $app_menu[$key];
                            if (property_exists($parentMenu, 'children')) {
                                array_push($parentMenu->children, $new_menu);
                            } else {
                                $parentMenu->{'children'} = [$new_menu];
                            }
                        }
                    }
                }
            }
            $menus->menu_list = $app_menu;
            $menus->save();
            if ($new_menu->type != 'parent') {
                foreach ($this->permissions as $permission) {
                    $this->permissionList[] = $new_menu->permission_key . '-' . $permission;
                }
                $revocableRoles = array_diff(explode(',', $previousAccess), explode(',', $new_menu->access));
                $assignableRoles = array_diff(explode(',', $new_menu->access), explode(',', $previousAccess));
                SELF::revokePermissions($revocableRoles);
                SELF::assignPermissions($assignableRoles);
            }
            return true;
        } catch (QueryException $e) {
            //database related exception
            return throw new  \Exception($e->errorInfo[2]);
        } catch (\Exception $e) {
            //general exception
            return throw new  \Exception($e->getMessage());
        }
    }

    public function remove($menuId)
    {
        $menuLink = MenuLink::find($menuId);
        if (strtolower($menuLink->link) == strtolower('menuLink.index')) {
            return throw new  \Exception('Deleting manage menu will crash application ');
        }
        $menus = Menu::first();
        $menus->menu_list = json_decode(request('menuList'));
        $menus->save();
        if ($menuLink->type == 'parent') {
            $childMenus = MenuLink::where('parent_id', $menuId)->get();
            foreach ($childMenus as $childMenu) {
                $menuName = Str::studly($childMenu->permission_key);
                foreach ($this->permissions as $permission) {
                    $this->permissionList[] = $menuName . '-' . $permission;
                    Permission::where('name', $menuName . '-' . $permission)->delete();
                }
                MenuLink::destroy($childMenu->id);
            }
        } else {
            $menuName = Str::studly($menuLink->permission_key);
            foreach ($this->permissions as $permission) {
                $this->permissionList[] = $menuName . '-' . $permission;
                Permission::where('name', $menuName . '-' . $permission)->delete();
            }
        }
        $menuLink->delete();
        app()->make(\Spatie\Permission\PermissionRegistrar::class)->forgetCachedPermissions();
    }

    public function listableMenu()
    {
        $existingMenu = Menu::pluck('menu_list')->first();
        $existingLink = [];
        $app_menu = json_decode($existingMenu);
        foreach ($app_menu as $menu) {
            if ($menu->type == 'parent-single') {
                $existingLink[] = $menu->link;
            } else {
                if (property_exists($menu, 'children')) {
                    foreach ($menu->children as $subMenu) {
                        $existingLink[] = $subMenu->link;
                    }
                }
            }
        }
        $routeCollection = Route::getRoutes();
        $menuRoutes = [];
        foreach ($routeCollection as $value) {
            if ($value->getActionMethod() == 'index' || $value->getActionMethod() == '__invoke') {
                $menuRoutes[] = $value->getName();
            }
        }
        return array_diff($menuRoutes, $existingLink);
    }

    public function createPermissions($menu)
    {
        foreach ($this->permissions as $permission) {
            $permissionName = $menu . '-' . $permission;
            $this->permissionList[] = $permissionName;
            Permission::create(['name' => $permissionName]);
        }
        //updating permission list to json file
        $targetPath = base_path('Modules/UserManagement/Database/Seeders/PermissionList.json');
        if (file_exists($targetPath)) {
            $permissionListCurrent = File::get($targetPath);
            $permissionListArr = json_decode($permissionListCurrent);
            $permissionListUpdated = json_encode(array_unique([...$permissionListArr,
                ...$this->permissionList]));
            file_put_contents($targetPath, $permissionListUpdated);
        }
        return;
    }

    public function assignPermissions($rolesArr)
    {
        if (!\is_array($rolesArr)) {
            $rolesArr = array_filter(explode(',', $rolesArr));
        }
        if (empty($rolesArr)) {
            return;
        }
        foreach ($rolesArr as $role) {
            $userRole = Role::where('name', $role)->first();
            $userRole->givePermissionTo($this->permissionList);
        }
        return;
    }

    public function revokePermissions($rolesArr)
    {
        if (!\is_array($rolesArr)) {
            $rolesArr = array_filter(explode(',', $rolesArr));
        }
        if (empty($rolesArr)) {
            return;
        }
        foreach ($rolesArr as $role) {
            $userRole = Role::where('name', $role)->first();
            $userRole->revokePermissionTo($this->permissionList);
        }
        return;
    }

    public function listRoleMenuPermission()
    {
        app()->make(\Spatie\Permission\PermissionRegistrar::class)->forgetCachedPermissions();
        $menuLinks = MenuLink::where('name', '!=', 'home')->where('type', '!=', 'parent')->get();
        $roles = Role::with('permissions')->get();
        $dataReturn = [];
        foreach ($menuLinks as $menuLink) {
            $linkId = $menuLink->id;
            $linkName = Str::studly($menuLink->permission_key);
            foreach ($roles as $role) {
                foreach ($this->permissions as $permission) {
                    $permissionName = $linkName . '-' . $permission;
                    if ($role->hasPermissionTo($permissionName)) {
                        $dataReturn[$role->name][$linkId][] = $permission;
                    }
                }
            }
        }
        return (object) $dataReturn;
    }

    public function updatePermission($menuId)
    {
        $menuLink = MenuLink::find($menuId);
        $linkName = $menuLink->permission_key;
        $role = request('role');
        $permissions = request('permission');
        $revokablePermissionName = $reassignPermissionName = [];
        $role = Role::where('name', $role)->first();
        foreach ($this->permissions as $permission) {
            $revokablePermissionName[] = $linkName . '-' . $permission;
        }
        $role->revokePermissionTo($revokablePermissionName);
        foreach ($permissions as $permission) {
            $reassignPermissionName[] = $linkName . '-' . $permission;
        }
        return $role->givePermissionTo($reassignPermissionName);
    }

    public function deletePermission($menuId)
    {
        $menuLink = MenuLink::find($menuId);
        $linkName = $menuLink->permission_key;
        $userRole = request('role');
        $revokablePermissionName = [];
        foreach ($this->permissions as $permission) {
            $revokablePermissionName[] = $linkName . '-' . $permission;
        }
        $role = Role::where('name', $userRole)->first();
        $role->revokePermissionTo($revokablePermissionName); //delete all permission to menu of a role
        //update menu link access
        $menuLinkRoles = explode(',', $menuLink->access);
        if (($key = array_search($userRole, $menuLinkRoles)) !== false) {
            unset($menuLinkRoles[$key]);
        }
        $menuLink->access = implode(',', $menuLinkRoles);
        $menuLink->save();
        //update menus json
        $roles = explode(',', $menuLink->access); //Role::pluck('name')->toArray();
        $assignableRoles = array_diff($roles, (array) $userRole);
        $new_menu = (object) [
            'name' => $menuLink->name,
            'link' => $menuLink->link,
            'icon' => $menuLink->icon,
            'parent_id' => $menuLink->parent_id,
            'type' => $menuLink->type,
            'access' => implode(',', $assignableRoles)
        ];

        $menus = Menu::first();
        $app_menu = json_decode($menus->menu_list);
        foreach ($app_menu as $key => $value) {
            if ($new_menu->type != 'child') {
                if ($value->id == $menuId) {
                    $app_menu[$key]->name = $new_menu->name;
                    $app_menu[$key]->link = $new_menu->link;
                    $app_menu[$key]->icon = $new_menu->icon;
                    $app_menu[$key]->type = $new_menu->type;
                    $app_menu[$key]->access = $new_menu->access;
                }
            } else {
                if ($value->id == $new_menu->parent_id) {
                    foreach ($app_menu[$key]->children as $subKey => $subValue) {
                        if ($subValue->id == $menuId) {
                            $app_menu[$key]->children[$subKey] = $new_menu;
                        }
                    }
                }
            }
        }
        $menus->menu_list = json_encode($app_menu);
        return $menus->save();
    }

    public function generateFiles($moduleName, $controllerName)
    {
        $entity = Module::findByName($moduleName);
        if ($entity) {
            $generatedRouteName = Str::camel($controllerName);
            Artisan::call('make:subModule ' . $moduleName . ' -c ' . $controllerName . ' -m');
            return $generatedRouteName;
        }
        $generatedRouteName = Str::camel($controllerName);
        Artisan::call('make:module ' . $moduleName . ' -c ' . $controllerName . ' -m -e');
        return $generatedRouteName;
    }
}
