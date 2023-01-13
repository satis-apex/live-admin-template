<?php
namespace Modules\EventManagement\Http\Controllers;

use Inertia\Inertia;
use Spatie\Permission\Models\Role;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Redirect;
use Modules\EventManagement\Models\Announcement;
use Modules\EventManagement\Http\Resources\AnnouncementResource;
use Facades\Modules\EventManagement\Services\AnnouncementService;

class AnnouncementController extends Controller
{
    protected $routeName = 'announcement';

    public function __construct()
    {
        $this->implementMethodPermission('Announcement');
    }

    public function index()
    {
        $announcementList = Announcement::latest()->get();
        $roleList = Role::where('name', '!=', 'Su-Admin')->pluck('name')->toArray();
        return Inertia::render(
            'EventManagement::Announcement/Index',
            [
                'breadcrumb' => getBreadcrumb() ?: readable('Announcement'),
                'announcementList' => AnnouncementResource::collection($announcementList)->toJson(),
                'roleList' => $roleList,
                'userCan' => [
                    'massAdd' => true && request()->user()->hasPermissionTo('Announcement-create'),
                    'create' => request()->user()->hasPermissionTo('Announcement-create'),
                    'edit' => request()->user()->hasPermissionTo('Announcement-edit'),
                    'delete' => request()->user()->hasPermissionTo('Announcement-delete'),
                ]
            ]
        );
    }

    public function store()
    {
        try {
            AnnouncementService::add();
        } catch (\Exception $e) {
            return Redirect::route($this->routeName . '.index')->with('error', $e->getMessage());
        }
        return Redirect::route($this->routeName . '.index')->with('success', 'Added Successfully');
    }

    public function massStore()
    {
        try {
            AnnouncementService::massAdd();
        } catch (\Exception $e) {
            return Redirect::route($this->routeName . '.index')->with('error', $e->getMessage());
        }
        return Redirect::route($this->routeName . '.index')->with('success', 'Multiple Data Added Successfully');
    }

    public function update(int $id)
    {
        try {
            AnnouncementService::update($id);
        } catch (\Exception $e) {
            return Redirect::route($this->routeName . '.index')->with('error', $e->getMessage());
        }
        return Redirect::route($this->routeName . '.index')->with('success', 'Updated Successfully');
    }

    public function destroy(int $id)
    {
        try {
            AnnouncementService::remove($id);
        } catch (\Exception $e) {
            return Redirect::route($this->routeName . '.index')->with('error', $e->getMessage());
        }
        return Redirect::route($this->routeName . '.index')->with('success', 'Deleted Successfully');
    }
}
