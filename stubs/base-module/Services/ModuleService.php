<?php

namespace Modules\{Module}\Services;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Modules\{Module}\Models\{Model};
use Illuminate\Database\QueryException;

class {Controller}Service
{
	public function add()
    {
        try {
            $insertData = [
                'name' => request('name')
            ];
			return {Model}::create($insertData);
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
                {Model}::create($row);
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
            ${model} = {Model}::find($id); 
            ${model}->name = request('name');
            return ${model}->save();
        } catch (QueryException $e) {
            return throw new  \Exception($e->errorInfo[2]);
        } catch (\Exception $e) {
            return throw new  \Exception($e->getMessage());
        }
    }

    public function remove($id)
    {
        try {
            return {Model}::destroy($id);
        } catch (QueryException $e) {
            return throw new  \Exception($e->errorInfo[2]);
        } catch (\Exception $e) {
            return throw new  \Exception($e->getMessage());
        }
    }
}