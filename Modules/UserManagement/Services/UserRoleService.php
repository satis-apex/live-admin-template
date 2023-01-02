<?php
namespace Modules\UserManagement\Services;

use Illuminate\Database\QueryException;
use Spatie\Permission\Models\Role;

class UserRoleService
{
    public function add()
    {
        try {
            $insertData = [
                'name' => request('name')
            ];
            return Role::create($insertData);
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
                Role::create($row);
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
            $userrole = Role::find($id);
            $userrole->name = request('name');
            return $userrole->save();
        } catch (QueryException $e) {
            return throw new  \Exception($e->errorInfo[2]);
        } catch (\Exception $e) {
            return throw new  \Exception($e->getMessage());
        }
    }

    public function remove($id)
    {
        try {
            return Role::destroy($id);
        } catch (QueryException $e) {
            return throw new  \Exception($e->errorInfo[2]);
        } catch (\Exception $e) {
            return throw new  \Exception($e->getMessage());
        }
    }
}
