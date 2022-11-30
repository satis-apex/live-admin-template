<?php

namespace Modules\DataTable\Services;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Modules\DataTable\Models\DataTable;
use Illuminate\Database\QueryException;

class DataTableService
{
    public function get()
    {
        // request()->validate([
        //     'direction' => ['in:asc,desc'],
        //     'field' => ['in:name,city']
        // ]);

        $pageSize = request('limit') ?? 25;
        $pageNumber = request('page');
        $filters = request('filters');
        $query = DataTable::query();
        if (request('keyword')) {
            $query->where('name', 'LIKE', '%' . request('keyword') . '%');
        }
        if (!empty($filters)) {
            foreach ($filters as $key => $filter) {
                $query->whereIn($key, $filter);
            }
        }
        if (request()->has(['field', 'direction'])) {
            $columnName = request('field');
            $orderBy = request('direction') == 'ascending' ? 'asc' : 'desc';
            $query->orderBy($columnName, $orderBy);
        }
        return $query->paginate($pageSize)->withQueryString();
    }

	public function add()
    {
        try {
            $insertData = [
                'name' => request('name')
            ];
			return DataTable::create($insertData);
        } catch (QueryException $e) {
            return throw new  \Exception($e->errorInfo[2]);
        } catch (\Exception $e) {
            return throw new  \Exception($e->getMessage());
        }
    }

	public function massAdd()
    {
        try {
            $insertData = request('massRecord');
            foreach ($insertData as $row) {
                DataTable::create($row);
            }
        } catch (QueryException $e) {
            return throw new  \Exception($e->errorInfo[2]);
        } catch (\Exception $e) {
            return throw new  \Exception($e->getMessage());
        }
    }

    public function update($id)
    {
        try {
            $datatable = DataTable::find($id); 
            $datatable->name = request('name');
            return $datatable->save();
        } catch (QueryException $e) {
            return throw new  \Exception($e->errorInfo[2]);
        } catch (\Exception $e) {
            return throw new  \Exception($e->getMessage());
        }
    }

    public function remove($id)
    {
        try {
            return DataTable::destroy($id);
        } catch (QueryException $e) {
            return throw new  \Exception($e->errorInfo[2]);
        } catch (\Exception $e) {
            return throw new  \Exception($e->getMessage());
        }
    }
}