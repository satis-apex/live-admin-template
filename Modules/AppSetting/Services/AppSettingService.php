<?php
namespace Modules\AppSetting\Services;

use Modules\AppSetting\Models\ApplicationInfo;
use Illuminate\Database\QueryException;

class AppSettingService
{
    public function update($id)
    {
        try {
            $appsetting = ApplicationInfo::find($id);
            $appsetting->name = request('name');
            return $appsetting->save();
        } catch (QueryException $e) {
            return throw new  \Exception($e->errorInfo[2]);
        } catch (\Exception $e) {
            return throw new  \Exception($e->getMessage());
        }
    }


}
