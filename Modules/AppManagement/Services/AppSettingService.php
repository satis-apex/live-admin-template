<?php
namespace Modules\AppManagement\Services;

use Modules\AppManagement\Models\ApplicationInfo;
use Illuminate\Database\QueryException;

class AppSettingService
{
    public function update()
    {
        try {
            $appsetting = ApplicationInfo::find(1);
            $appsetting->title = request('title');
            $appsetting->email = request('email');
            $appsetting->contact = request('contact');
            $appsetting->address = request('address');
            $appsetting->primary_color = request('primaryColor');
            $appsetting->primary_light_color = request('primaryLightColor');
            $appsetting->primary_dark_color = request('primaryDarkColor');
            $appsetting->complementary_color = request('complementaryColor');

            if (request()->has('logo') && request('logo') != '') {
                $appsetting->addMediaFromRequest('logo')->toMediaCollection('logo');
            }
            if (request()->has('favLight') && request('favLight') != '') {
                $appsetting->addMediaFromRequest('favLight')->toMediaCollection('fav-icon-light');
            }
            if (request()->has('favDark') && request('favDark') != '') {
                $appsetting->addMediaFromRequest('favDark')->toMediaCollection('fav-icon-dark');
            }
            return $appsetting->save();
        } catch (QueryException $e) {
            return throw new  \Exception($e->errorInfo[2]);
        } catch (\Exception $e) {
            return throw new  \Exception($e->getMessage());
        }
    }
}
