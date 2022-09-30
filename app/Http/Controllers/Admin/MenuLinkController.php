<?php

namespace App\Http\Controllers\Admin;

use App\Models\Menu;
use Inertia\Inertia;
use App\Models\Roles;
use App\Models\MenuLink;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Redirect;

class MenuLinkController
{
	public function index()
	{
		$routeCollection = Route::getRoutes();
		$menuRoutes = [];
		$parentLinks = MenuLink::where('type', "parent")->get(['id', 'name']);
		$userRoles = Roles::where('guard_name', 'web')->orderBy('id')->pluck('name')->toArray();
		foreach ($routeCollection as $value) {
			if ($value->getActionMethod() == "index" || $value->getActionMethod() == "__invoke")
				$menuRoutes[] = $value->getName();
		}
		return Inertia::render(
			'Admin/MenuManage/Index',
			[
				"menuRoutes" => $menuRoutes,
				"parentLinks" => $parentLinks,
				"userRoles" => $userRoles
			]
		);
	}

	public function store()
	{
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
		return Redirect::route('menu-link.index');
	}

	public function update($menuId)
	{
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
					// $parentMenu = $app_menu[$key];
					// if (property_exists($parentMenu, 'children')) {
					// 	array_push($app_menu[$key]->children, (object)$new_menu);
					// } else {
					// 	$parentMenu->{'children'} = [(object)$new_menu];
					// }
				}
			}
		}
		$menus->menu_list = $app_menu;
		$menus->save();
		return Redirect::route('menu-link.index');
		//  MenuLink::where('parent_id', $menuId)->delete();


	}
	public function destroy($menuId)
	{
		$menuLink = MenuLink::find($menuId);
		if ($menuLink->type == 'parent') {
			MenuLink::where('parent_id', $menuId)->delete();
		}
		$menuLink->delete();
		$menus = Menu::first();
		$menus->menu_list = json_decode(request('menuList'));
		$menus->save();
		return Redirect::route('menu-link.index');
	}
}
