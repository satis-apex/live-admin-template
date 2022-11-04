<?php

namespace Modules\User\Services;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Database\QueryException;

class UserService
{
	public function add()
    {
        try {
			#
        } catch (QueryException $e) {
            return throw new  \Exception($e->errorInfo[2]);
        } catch (\Exception $e) {
            return throw new  \Exception($e->getMessage());
        }
    }

    public function update($id)
    {
        try {
           #
        } catch (QueryException $e) {
            return throw new  \Exception($e->errorInfo[2]);
        } catch (\Exception $e) {
            return throw new  \Exception($e->getMessage());
        }
    }

    public function remove($id)
    {
        try {
			#
        } catch (QueryException $e) {
            return throw new  \Exception($e->errorInfo[2]);
        } catch (\Exception $e) {
            return throw new  \Exception($e->getMessage());
        }
    }
}