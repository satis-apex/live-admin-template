<?php

namespace Modules\{Module}\Http\Controllers;

use Inertia\Inertia;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Redirect;
use Facades\Modules\{Module}\Services\{Module}Service;

class {Module}Controller extends Controller
{
    protected $routeName = '{routeName}';
    public function __construct()
    {
        $this->implementMethodPermission('{Module}');
    }

    public function index()
    {
        return Inertia::render(
			'{Module}::Index',
			[
				'breadcrumb' => getBreadcrumb() ?: readable('{Module}'),
				'userCan' => [
                    'create' => request()->user()->hasPermissionTo('{Module}-create'),
                    'edit' => request()->user()->hasPermissionTo('{Module}-edit'),
                    'delete' => request()->user()->hasPermissionTo('{Module}-delete'),
                ]
			]
		);
    }

    public function store()
    {
		try {
           {Module}Service::add();
        } catch (\Exception $e) {
            return Redirect::route($this->routeName.'.index')->with('error', $e->getMessage());
        }
        return Redirect::route($this->routeName.'.index')->with('success', ' Added Successfully');
    }

    public function update(int $id)
    {
		 try {
           {Module}Service::update($id);
        } catch (\Exception $e) {
            return Redirect::route($this->routeName.'.index')->with('error', $e->getMessage());
        }
        return Redirect::route($this->routeName.'.index')->with('success', ' Updated Successfully');
    }

    public function destroy(int $id)
    {
		 try {
            {Module}Service::remove($id);
        } catch (\Exception $e) {
            return Redirect::route($this->routeName.'.index')->with('error', $e->getMessage());
        }
        return Redirect::route($this->routeName.'.index')->with('success', ' Deleted Successfully');
    }
}
