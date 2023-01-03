<?php

namespace Modules\{Module}\Http\Controllers;

use Inertia\Inertia;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Redirect;
use Facades\Modules\{Module}\Services\{Controller}Service;
use Modules\{Module}\Models\{Model};

class {Controller}Controller extends Controller
{
    protected $routeName = '{routeName}';
    public function __construct()
    {
        $this->implementMethodPermission('{Controller}');
    }

    public function index()
    {
        ${Controller}List = [
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
			'{Module}::{Controller}/Index',
			[
				'breadcrumb' => getBreadcrumb() ?: readable('{Controller}'),
                '{Controller}List' => ${Controller}List,
				'userCan' => [
                    'massAdd' => true && request()->user()->hasPermissionTo('{Controller}-create'),
                    'create' => request()->user()->hasPermissionTo('{Controller}-create'),
                    'edit' => request()->user()->hasPermissionTo('{Controller}-edit'),
                    'delete' => request()->user()->hasPermissionTo('{Controller}-delete'),
                ]
			]
		);
    }

    public function store()
    {
		try {
           {Controller}Service::add();
        } catch (\Exception $e) {
            return Redirect::route($this->routeName.'.index')->with('error', $e->getMessage());
        }
        return Redirect::route($this->routeName.'.index')->with('success', 'Added Successfully');
    }

    public function massStore()
    {
		try {
           {Controller}Service::massAdd();
        } catch (\Exception $e) {
            return Redirect::route($this->routeName.'.index')->with('error', $e->getMessage());
        }
        return Redirect::route($this->routeName.'.index')->with('success', 'Multiple Data Added Successfully');
    }

    public function update(int $id)
    {
		 try {
           {Controller}Service::update($id);
        } catch (\Exception $e) {
            return Redirect::route($this->routeName.'.index')->with('error', $e->getMessage());
        }
        return Redirect::route($this->routeName.'.index')->with('success', 'Updated Successfully');
    }

    public function destroy(int $id)
    {
		 try {
            {Controller}Service::remove($id);
        } catch (\Exception $e) {
            return Redirect::route($this->routeName.'.index')->with('error', $e->getMessage());
        }
        return Redirect::route($this->routeName.'.index')->with('success', 'Deleted Successfully');
    }
}
