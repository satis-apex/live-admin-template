<?php

namespace Modules\DataTable\Http\Controllers;

use Inertia\Inertia;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Redirect;
use Facades\Modules\DataTable\Services\DataTableService;
use Modules\DataTable\Models\DataTable;

class DataTableController extends Controller
{
    protected $routeName = 'dataTable';
    public function __construct()
    {
        $this->implementMethodPermission('DataTable');
    }

    public function index()
    {
        $dataTableList = DataTableService::get(); // DataTable::query()->get('name');
        return Inertia::render(
			'DataTable::Index',
			[
                'dataQuery' => request()->all(['limit', 'filters', 'keyword', 'field', 'direction']),
				'breadcrumb' => getBreadcrumb() ?: readable('DataTable'),
                'dataTableList' => $dataTableList,
				'userCan' => [
                    'massAdd' => true && request()->user()->hasPermissionTo('DataTable-create'),
                    'create' => request()->user()->hasPermissionTo('DataTable-create'),
                    'edit' => request()->user()->hasPermissionTo('DataTable-edit'),
                    'delete' => request()->user()->hasPermissionTo('DataTable-delete'),
                ]
			]
		);
    }

    public function store()
    {
		try {
           DataTableService::add();
        } catch (\Exception $e) {
            return Redirect::route($this->routeName.'.index')->with('error', $e->getMessage());
        }
        return Redirect::route($this->routeName.'.index')->with('success', 'Added Successfully');
    }

    public function massStore()
    {
		try {
           DataTableService::massAdd();
        } catch (\Exception $e) {
            return Redirect::route($this->routeName.'.index')->with('error', $e->getMessage());
        }
        return Redirect::route($this->routeName.'.index')->with('success', 'Multiple Data Added Successfully');
    }

    public function update(int $id)
    {
		 try {
           DataTableService::update($id);
        } catch (\Exception $e) {
            return Redirect::route($this->routeName.'.index')->with('error', $e->getMessage());
        }
        return Redirect::route($this->routeName.'.index')->with('success', 'Updated Successfully');
    }

    public function destroy(int $id)
    {
		 try {
            DataTableService::remove($id);
        } catch (\Exception $e) {
            return Redirect::route($this->routeName.'.index')->with('error', $e->getMessage());
        }
        return Redirect::route($this->routeName.'.index')->with('success', 'Deleted Successfully');
    }
}
