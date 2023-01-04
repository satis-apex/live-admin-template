<?php
namespace Modules\AppManagement\Http\Controllers;

use Inertia\Inertia;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Redirect;
use Facades\Modules\AppManagement\Services\AppSettingService;
use Modules\AppManagement\Models\ApplicationInfo;

class AppSettingController extends Controller
{
    protected $routeName = 'appSetting';

    public function __construct()
    {
        $this->implementMethodPermission('AppSetting');
    }

    public function index()
    {
        $appInfo = ApplicationInfo::query()->first();

        return Inertia::render(
            'AppManagement::Index',
            [
                'breadcrumb' => getBreadcrumb() ?: readable('AppSetting'),
                'appInfo' => $appInfo,
                'userCan' => [
                    'massAdd' => false && request()->user()->hasPermissionTo('AppSetting-create'),
                    'create' => request()->user()->hasPermissionTo('AppSetting-create'),
                    'edit' => request()->user()->hasPermissionTo('AppSetting-edit'),
                    'delete' => request()->user()->hasPermissionTo('AppSetting-delete'),
                ]
            ]
        );
    }

    public function update()
    {
        try {
            AppSettingService::update();
        } catch (\Exception $e) {
            return Redirect::route($this->routeName . '.index')->with('error', $e->getMessage());
        }
        return Redirect::route($this->routeName . '.index')->with('success', 'Application Information Updated Successfully');
    }
}
