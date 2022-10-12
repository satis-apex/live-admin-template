<?php

namespace App\Http\Controllers\Admin;

use App\Models\Menu;
use Inertia\Inertia;
use App\Models\Roles;
use App\Models\MenuLink;
use Facades\App\Services\Menu\MenuLinkService;
use Exception;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Redirect;
use Spatie\Permission\Models\Role;

class MenuLinkController
{
	public function index()
	{
		$menuRoutes = MenuLinkService::listableMenu();
		$parentLinks = MenuLink::where('type', "parent")->get(['id', 'name']);
		$userRoles = Role::where('guard_name', 'web')->orderBy('id')->pluck('name')->toArray();
		$roleMenus =  MenuLinkService::listRoleMenuPermission();
		$menuLinkLists =  MenuLink::pluck('name')->toArray();
		return Inertia::render(
			'Admin/MenuManage/Index',
			[
				"menuRoutes" => $menuRoutes,
				"parentLinks" => $parentLinks,
				"userRoles" => $userRoles,
				"roleMenus" => $roleMenus,
				"menuLinkLists" => $menuLinkLists
			]
		);
	}
	public function store()
	{
		try {
			MenuLinkService::add();
		} catch (\Exception $e) {
			return Redirect::route('menu-link.index')->with('error', $e->getMessage());
		}
		return Redirect::route('menu-link.index')->with('success', "Menu Link Added Successfully");
	}
	public function update($menuId)
	{
		try {
			MenuLinkService::update($menuId);
		} catch (\Exception $e) {
			return Redirect::route('menu-link.index')->with('error', $e->getMessage());
		}
		return Redirect::route('menu-link.index')->with('success', "Menu Link Updates Successfully");
	}
	public function destroy($menuId)
	{
		try {
			MenuLinkService::remove($menuId);
		} catch (\Exception $e) {
			return Redirect::route('menu-link.index')->with('error', $e->getMessage());
		}
		return Redirect::route('menu-link.index')->with('success', "Menu Link Deleted Successfully");
	}
}
