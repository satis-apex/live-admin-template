<?php

namespace App\Http\Controllers\Admin;

use Facades\App\Services\Menu\MenuLinkService;
use Illuminate\Support\Facades\Redirect;

class MenuLinkPermissionController
{
	public function update($menuId)
	{
		try {
			MenuLinkService::updatePermission($menuId);
		} catch (\Exception $e) {
			return Redirect::route('menu-link.index')->with('error', $e->getMessage());
		}

		return Redirect::route('menu-link.index')->with('success', "Menu link permission updated successfully");
	}
	public function destroy($menuId)
	{
		try {
			MenuLinkService::deletePermission($menuId);
		} catch (\Exception $e) {
			return Redirect::route('menu-link.index')->with('error', $e->getMessage());
		}

		return Redirect::route('menu-link.index')->with('success', "Menu link permission revoked successfully");
	}
}
