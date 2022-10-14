<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Redirect;
use Facades\App\Services\MenuLinkService;

class MenuLinkPermissionController extends Controller
{
    public function __construct()
    {
        $this->implementMethodPermission('MenuLink');
    }

    public function update($menuId)
    {
        try {
            MenuLinkService::updatePermission($menuId);
        } catch (\Exception $e) {
            return Redirect::route('menu-link.index')->with('error', $e->getMessage());
        }

        return Redirect::route('menu-link.index')->with('success', 'Menu link permission updated successfully');
    }

    public function destroy($menuId)
    {
        try {
            MenuLinkService::deletePermission($menuId);
        } catch (\Exception $e) {
            return Redirect::route('menu-link.index')->with('error', $e->getMessage());
        }

        return Redirect::route('menu-link.index')->with('success', 'Menu link permission revoked successfully');
    }
}
