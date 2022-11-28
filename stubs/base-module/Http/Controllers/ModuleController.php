<?php

namespace Modules\{Module}\Http\Controllers;

use Inertia\Inertia;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Redirect;
use Facades\Modules\{Module}\Services\{Module}Service;
use Modules\{Module}\Models\{Model};

class {Module}Controller extends Controller
{
    protected $routeName = '{routeName}';
    public function __construct()
    {
        $this->implementMethodPermission('{Module}');
    }

    public function index()
    {
        ${moduleC}List = [
            [
                'date' => '2016-05-03',
                'name' => 'Tom',
                'address' => 'No. 189, Grove St, Los Angeles',
                'status' => 'Active',
            ],
            [
                'date' => '2016-05-02',
                'name' => 'John',
                'address' => 'No. 199, Grove St, Los Angeles',
                'status' => 'Active',
            ],
        ]; // {Model}::query()->get('name');
        return Inertia::render(
			'{Module}::Index',
			[
				'breadcrumb' => getBreadcrumb() ?: readable('{Module}'),
                '{moduleC}List' => ${moduleC}List,
				'userCan' => [
                    'massAdd' => true && request()->user()->hasPermissionTo('{Module}-create'),
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
        return Redirect::route($this->routeName.'.index')->with('success', 'Added Successfully');
    }

    public function massStore()
    {
		try {
           {Module}Service::massAdd();
        } catch (\Exception $e) {
            return Redirect::route($this->routeName.'.index')->with('error', $e->getMessage());
        }
        return Redirect::route($this->routeName.'.index')->with('success', 'Multiple Data Added Successfully');
    }

    public function update(int $id)
    {
		 try {
           {Module}Service::update($id);
        } catch (\Exception $e) {
            return Redirect::route($this->routeName.'.index')->with('error', $e->getMessage());
        }
        return Redirect::route($this->routeName.'.index')->with('success', 'Updated Successfully');
    }

    public function destroy(int $id)
    {
		 try {
            {Module}Service::remove($id);
        } catch (\Exception $e) {
            return Redirect::route($this->routeName.'.index')->with('error', $e->getMessage());
        }
        return Redirect::route($this->routeName.'.index')->with('success', 'Deleted Successfully');
    }
}
