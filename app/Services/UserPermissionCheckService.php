<?php

namespace App\Services;

use Illuminate\Database\QueryException;

Class UserPermissionCheckService
{
	public function add()
    {
        try {
			//
		} catch (QueryException $e) {
            return throw new  \Exception($e->errorInfo[2]);
        } catch (\Exception $e) {
            return throw new  \Exception($e->getMessage());
        }
    }
	public function update($id)
    {
        try {
			//
		} catch (QueryException $e) {
            return throw new  \Exception($e->errorInfo[2]);
        } catch (\Exception $e) {
            return throw new  \Exception($e->getMessage());
        }
    }
	public function remove($id)
    {
        try {
			//
		} catch (QueryException $e) {
            return throw new  \Exception($e->errorInfo[2]);
        } catch (\Exception $e) {
            return throw new  \Exception($e->getMessage());
        }
    }
}