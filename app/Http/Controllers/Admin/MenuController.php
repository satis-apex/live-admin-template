<?php
namespace App\Http\Controllers\Admin;

use App\Models\Menu;
use App\Models\MenuLink;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Redirect;

class MenuController extends Controller
{
    public function __construct()
    {
        $this->implementMethodPermission('manage-menu');
    }

    public function store()
    {
        try {
            $menus = Menu::first();
            $menus->menu_list = request('menus');
            $menus->save();
            foreach (request('changedMenu') as $id => $parent) {
                $menuLink = MenuLink::Find($id);
                $menuLink->parent_id = $parent;
                $menuLink->save();
            }
        } catch (\Exception $e) {
            return Redirect::route('menu-link.index')->with('error', $e->getMessage());
        }
        return Redirect::route('menu-link.index')->with('success', 'Menu Updated Successfully');
    }
}
