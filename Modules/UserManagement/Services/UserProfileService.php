<?php
namespace Modules\UserManagement\Services;

use Modules\UserManagement\Models\UserInfo;
use Illuminate\Database\QueryException;

class UserProfileService
{
    public function add()
    {
        try {
        } catch (QueryException $e) {
            return throw new  \Exception($e->errorInfo[2]);
        } catch (\Exception $e) {
            return throw new  \Exception($e->getMessage());
        }
    }

    public function update($id)
    {
        try {
            $userInfo = UserInfo::find($id);
            $userInfo->first_name = request('firstName');
            $userInfo->middle_name = request('middleName');
            $userInfo->last_name = request('lastName');
            $userInfo->contact = request('contact');
            $userInfo->gender = request('gender');
            return $userInfo->save();
        } catch (QueryException $e) {
            return throw new  \Exception($e->errorInfo[2]);
        } catch (\Exception $e) {
            return throw new  \Exception($e->getMessage());
        }
    }

    public function remove($id)
    {
        try {
        } catch (QueryException $e) {
            return throw new  \Exception($e->errorInfo[2]);
        } catch (\Exception $e) {
            return throw new  \Exception($e->getMessage());
        }
    }
}
