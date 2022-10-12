<?php

namespace App\Services\Menu;

use Exception;
use App\Models\Menu;
use App\Models\MenuLink;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Route;
use Illuminate\Database\QueryException;
use Spatie\Permission\Models\Permission;

class MenuLinkService
{
	protected $permissions = ['access', 'create', 'edit', 'delete'];
	protected $permissionList = [];

	public function add()
	{
		try {
			$createdMenu = MenuLink::create(
				[
					'name' => request('name'),
					'link' => request('link'),
					'icon' => request('icon'),
					'parent_id' => request('parentId'),
					'type' => request('type'),
					'access' => implode(",", request('access'))
				]
			);
			$new_menu = (object)[
				"id" => $createdMenu->id,
				"link" => $createdMenu->link,
				"name" => $createdMenu->name,
				"icon" => $createdMenu->icon,
				"type" =>  $createdMenu->type,
				"access" => $createdMenu->access,
				"parent_id" => $createdMenu->parent_id
			];
			$menus = Menu::first();
			$app_menu = json_decode($menus->menu_list);
			if ($createdMenu->type == "parent" ||  $createdMenu->type == "parent-single") {
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
			if ($createdMenu->type != "parent") {
				SELF::createPermissions(Str::kebab($createdMenu->name));
				SELF::assignPermissions($createdMenu->access);
			}
			return true;
		} catch (QueryException $e) {
			//database related exception
			return throw new  Exception($e->errorInfo[2]);
		} catch (\Exception $e) {
			//general exception
			return throw new  Exception($e->getMessage());
		}
	}
	public function update($menuId)
	{
		try {
			$new_menu = (object) [
				'name' => request('name'),
				'link' => request('link'),
				'icon' => request('icon'),
				'parent_id' => request('parentId'),
				'type' => request('type'),
				'access' => implode(",", request('access'))
			];
			$menuLink = MenuLink::find($menuId);
			$menuLink->name = $new_menu->name;
			$menuLink->access = $new_menu->access;
			switch ($menuLink->type) {
				case 'child':
					$oldParent = $menuLink->parent_id;
					$menuLink->link = $new_menu->link;
					$menuLink->parent_id = $new_menu->parent_id;
					$new_menu->{'id'} = $menuId;
					break;
				case 'parent':
					$menuLink->icon = $new_menu->icon;
					break;
				case 'parent-single':
					$menuLink->link = $new_menu->link;
					$menuLink->icon = $new_menu->icon;
					break;
				default:
					break;
			}
			$menuLink->save();
			$menus = Menu::first();
			$app_menu = json_decode($menus->menu_list);
			foreach ($app_menu as $key => $value) {
				if ($new_menu->type != "child") {
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
							if ($subValue->id ==  $menuId) {
								$app_menu[$key]->children[$subKey] = $new_menu;
							}
						}
					} else if ($oldParent != $new_menu->parent_id) {
						if ($value->id == $oldParent) {
							foreach ($app_menu[$key]->children as $subKey => $subValue) {
								if ($subValue->id ==  $menuId) {
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
			if ($new_menu->type != "parent") {
				foreach ($this->permissions as $permission) {
					$this->permissionList[] = Str::kebab($new_menu->name) . "-" . $permission;
				}
				SELF::assignPermissions($new_menu->access);
			}
			return true;
		} catch (QueryException $e) {
			//database related exception
			return throw new  Exception($e->errorInfo[2]);
		} catch (\Exception $e) {
			//general exception
			return throw new  Exception($e->getMessage());
		}
	}
	public function remove($menuId)
	{
		$menus = Menu::first();
		$menus->menu_list = json_decode(request('menuList'));
		$menus->save();
		$menuLink = MenuLink::find($menuId);
		if ($menuLink->type == 'parent') {
			$childMenus = MenuLink::where('parent_id', $menuId)->get();
			foreach ($childMenus as $childMenu) {
				$menuName = Str::kebab($childMenu->name);
				foreach ($this->permissions as $permission) {
					$this->permissionList[] = $menuName . "-" . $permission;
					Permission::where('name', $menuName . "-" . $permission)->delete();
				}
				MenuLink::destroy($childMenu->id);
			}
		} else {
			$menuName = Str::kebab($menuLink->name);
			foreach ($this->permissions as $permission) {
				$this->permissionList[] = $menuName . "-" . $permission;
				Permission::where('name', $menuName . "-" . $permission)->delete();
			}
		}
		$menuLink->delete();
		app()->make(\Spatie\Permission\PermissionRegistrar::class)->forgetCachedPermissions();
	}
	public function listableMenu()
	{
		$routeCollection = Route::getRoutes();
		$menuRoutes = [];
		foreach ($routeCollection as $value) {
			if ($value->getActionMethod() == "index" || $value->getActionMethod() == "__invoke")
				$menuRoutes[] = $value->getName();
		}
		return $menuRoutes;
	}
	public function createPermissions($menu)
	{
		foreach ($this->permissions as $permission) {
			$this->permissionList[] = $menu . "-" . $permission;
			Permission::create(['name' => $menu . "-" . $permission]);
		}
		return;
	}
	public function assignPermissions($roles)
	{
		$rolesArr = explode(',', $roles);
		foreach ($rolesArr as $role) {
			$userRole = Role::where('name',  $role)->first();
			$userRole->givePermissionTo($this->permissionList);
		}
		return;
	}
	public function listRoleMenuPermission()
	{
		$menuLinks = MenuLink::where('name', '!=', 'home')->where('type', '!=', 'parent')->get();
		$roles = Role::all();
		$dataReturn = [];
		foreach ($menuLinks as $menuLink) {
			// $linkRole = explode(',', $menuLink->access);
			$linkId = $menuLink->id;
			$linkName = Str::kebab($menuLink->name);
			// $permissionNames = [];
			foreach ($roles as $role) {
				foreach ($this->permissions as $permission) {
					$permissionName =  $linkName . "-" . $permission;
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
		$linkName = Str::kebab($menuLink->name);
		$role = request('role');
		$permissions = request('permission');
		$revokablePermissionName = $reassignPermissionName = [];
		$role = Role::where('name', $role)->first();
		foreach ($this->permissions as $permission) {
			$revokablePermissionName[] =  $linkName . "-" . $permission;
		}
		$role->revokePermissionTo($revokablePermissionName);
		foreach ($permissions as $permission) {
			$reassignPermissionName[] =  $linkName . "-" . $permission;
		}
		return $role->givePermissionTo($reassignPermissionName);
	}
	public function deletePermission($menuId)
	{
		$menuLink = MenuLink::find($menuId);
		$linkName = Str::kebab($menuLink->name);
		$userRole = request('role');
		$revokablePermissionName = [];
		foreach ($this->permissions as $permission) {
			$revokablePermissionName[] =  $linkName . "-" . $permission;
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
			'parent_id' => $menuLink->parentId,
			'type' => $menuLink->type,
			'access' => implode(",", $assignableRoles)
		];
		$menus = Menu::first();
		$app_menu = json_decode($menus->menu_list);
		foreach ($app_menu as $key => $value) {
			if ($new_menu->type != "child") {
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
						if ($subValue->id ==  $menuId) {
							$app_menu[$key]->children[$subKey] = $new_menu;
						}
					}
				}
			}
		}
		$menus->menu_list = $app_menu;
		$menus->save();
	}
}
