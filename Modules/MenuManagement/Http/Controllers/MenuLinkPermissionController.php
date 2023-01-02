<?php
namespace Modules\MenuManagement\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Redirect;
use Facades\Modules\MenuManagement\Services\MenuManagementService;

class MenuLinkPermissionController extends Controller
{
    public function __construct()
    {
        $this->implementMethodPermission('MenuLink');
    }

    public function update($menuId)
    {
        try {
            MenuManagementService::updatePermission($menuId);
        } catch (\Exception $e) {
            return Redirect::route('menuLink.index')->with('error', $e->getMessage());
        }

        return Redirect::route('menuLink.index')->with('success', 'Menu link permission updated successfully');
    }

    public function destroy($menuId)
    {
        try {
            MenuManagementService::deletePermission($menuId);
        } catch (\Exception $e) {
            return Redirect::route('menuLink.index')->with('error', $e->getMessage());
        }

        return Redirect::route('menuLink.index')->with('success', 'Menu link permission revoked successfully');
    }
}
