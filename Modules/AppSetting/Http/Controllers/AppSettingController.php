<?php
namespace Modules\AppSetting\Http\Controllers;

use Inertia\Inertia;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Redirect;
use Facades\Modules\AppSetting\Services\AppSettingService;
use Modules\AppSetting\Models\ApplicationInfo;

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
            'AppSetting::Index',
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
        dd($_FILES['logo']);
        try {
            AppSettingService::update();
        } catch (\Exception $e) {
            return Redirect::route($this->routeName . '.index')->with('error', $e->getMessage());
        }
        return Redirect::route($this->routeName . '.index')->with('success', 'Updated Successfully');
    }
}
